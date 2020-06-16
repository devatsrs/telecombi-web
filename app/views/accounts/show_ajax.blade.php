 @if(count($response)>0)
            <?php  foreach($response as  $rows){
			  $rows = json_decode(json_encode($rows), True); //convert std array to simple array
			   ?>
            @if(isset($rows['Timeline_type']) && $rows['Timeline_type']==Task::Mail)
            <li id="timeline-{{$key}}" class="count-li timeline_mail_entry">
              <time class="cbp_tmtime" datetime="<?php echo date("Y-m-d h:i",strtotime($rows['created_at'])); ?>">
                <?php if(date("Y-m-d h:i",strtotime($rows['created_at'])) == date('Y-m-d h:i')) { ?>
                <span>Now</span>
                <?php }else{ ?>
                <span><?php echo date("h:i a",strtotime($rows['created_at']));  ?></span> <span>
                <?php if(date("Y-m-d",strtotime($rows['created_at'])) == date('Y-m-d')){echo "Today";}else{echo date("Y-m-d",strtotime($rows['created_at']));} ?>
                </span>
                <?php } ?>
              </time>
              <div id_toggle="{{$key}}" class="cbp_tmicon bg-gold"> <i class="entypo-mail"></i> </div>
              <div class="cbp_tmlabel normal_tag"> 
              <a email_number="{{$rows['AccountEmailLogID']}}" action_type="forward" class="pull-right edit-deal email_action" title="Forward"><i class="entypo-forward"></i></a>            
         <a email_number="{{$rows['AccountEmailLogID']}}" action_type="reply-all" class=" pull-right edit-deal email_action" title="Reply All"><i class="entypo-reply-all"></i></a>           
         <a email_number="{{$rows['AccountEmailLogID']}}" action_type="reply" class="pull-right edit-deal email_action" title="Reply"><i class="entypo-reply"></i></a> 
                <h2 class="toggle_open" id_toggle="{{$key}}">@if($rows['CreatedBy']==$current_user_title) You @else {{$rows['CreatedBy']}}  @endif <span>sent an email to</span> @if($rows['EmailToName']==$current_user_title) You @else {{$rows['EmailToName']}}  @endif <br> <p class="mail_subject">Subject: {{$rows['EmailSubject']}}</p>
</h2>
                <div id="hidden-timeline-{{$key}}" class="details no-display">
                  @if($rows['EmailCc'])<p>CC: {{$rows['EmailCc']}}</p>@endif
                  @if($rows['EmailBcc'])<p>BCC: {{$rows['EmailBcc']}}</p>@endif
                  <?php
	  if($rows['EmailAttachments']!='')
	  {
    		$attachments = unserialize($rows['EmailAttachments']);
			
			if(count($attachments)>0)
			{
				 echo "<p>Attachments: ";
				foreach($attachments as $key_acttachment => $attachments_data)
				{
					//
					/*if(is_amazon() == true)
					{
						$Attachmenturl =  AmazonS3::preSignedUrl($attachments_data['filepath']);
					}
					else
					{
						$Attachmenturl = CompanyConfiguration::get('UPLOAD_PATH')."/".$attachments_data['filepath'];
					}*/
                    $Attachmenturl = URL::to('emails/'.$rows['AccountEmailLogID'].'/getattachment/'.$key_acttachment);
					if($key_acttachment==(count($attachments)-1)){
						echo "<a target='_blank' href=".$Attachmenturl.">".$attachments_data['filename']."</a><br><br>";
					}else{
						echo "<a target='_blank' href=".$Attachmenturl.">".$attachments_data['filename']."</a><br>";
					}
					
				}
				echo "</p>";
			}			
	  }	 
	   ?>
                  <p class="mail_message">Messsage:<br>{{$rows['EmailMessage']}}</p><br>
                  <p><a data_fetch_id="{{$rows['AccountEmailLogID']}}" conversations_type="mail"  class="ticket_conversations">View Conversation</a></p>
                </div>
              </div>
            </li>
            @elseif(isset($rows['Timeline_type']) && $rows['Timeline_type']==Task::Tasks)
            <li id="timeline-{{$key}}" class="count-li timeline_task_entry @if($rows['followup_task'])followup_task @endif">
              <time class="cbp_tmtime" datetime="<?php echo date("Y-m-d h:i",strtotime($rows['created_at'])); ?>">
                <?php if(date("Y-m-d h:i",strtotime($rows['created_at'])) == date('Y-m-d h:i')) { ?>
                <span>Now</span>
                <?php }else{ ?>
                <span><?php echo date("h:i a",strtotime($rows['created_at']));  ?></span> <span>
                <?php if(date("Y-m-d",strtotime($rows['created_at'])) == date('Y-m-d')){echo "Today";}else{echo date("Y-m-d",strtotime($rows['created_at']));} ?>
                </span>
                <?php } ?>
              </time>
              <div id_toggle="{{$key}}" class="cbp_tmicon bg-info"> <i class="entypo-tag"></i> </div> 
              <div class="cbp_tmlabel @if(!$rows['followup_task']) normal_tag @endif ">  
                <a id="edit_task_{{$rows['TaskID']}}" task-id="{{$rows['TaskID']}}"  key_id="{{$key}}" class="pull-right edit-deal edit_task_link"><i class="entypo-pencil"></i>&nbsp;</a>
            <a id="delete_task_{{$rows['TaskID']}}" task-id="{{$rows['TaskID']}}"  key_id="{{$key}}" class="pull-right edit-deal delete_task_link"><i class="entypo-trash"></i></a>
            <h2 class="toggle_open" id_toggle="{{$key}}">
                @if($rows['TaskPriority']=='High')  <i class="edit-deal entypo-record" style="color:#d52a1a;font-size:15px;"></i> @endif
                @if($rows['CreatedBy']==$current_user_title && $rows['TaskName']==$current_user_title)<span>You created a @if($rows['followup_task']) follow up @endif task</span>
                 @elseif ($rows['CreatedBy']==$current_user_title && $rows['TaskName']!=$current_user_title)<span>You assign @if($rows['followup_task']) follow up @endif task to {{$rows['TaskName']}} </span> 
                 @elseif ($rows['CreatedBy']!=$current_user_title && $rows['TaskName']==$current_user_title)<span> {{$rows['CreatedBy']}} assign @if($rows['followup_task']) follow up @endif task to  You </span>
                 @else  <span> {{$rows['CreatedBy']}} assign @if($rows['followup_task']) follow up @endif task to  {{$rows['TaskName']}} </span> 
                 @endif
</h2>
               <div id="hidden-timeline-{{$key}}"  class="details no-display">
                  <p>Subject: {{$rows['TaskTitle']}}</p>
                  <p>Assign To: {{$rows['TaskName']}}</p>
                  <p>priority: {{$rows['TaskPriority']}}</p>
                  <p>Due Date: {{$rows['DueDate']}}</p>
                  <p>Status: {{$rows['TaskStatus']}}. </p>
                  <p>Description: {{$rows['TaskDescription']}} </p>
                </div>
              </div>
            </li>
            @elseif(isset($rows['Timeline_type']) && $rows['Timeline_type']==Task::Note)
            <li id="timeline-{{$key}}" class="count-li timeline_note_entry">
              <time class="cbp_tmtime" datetime="<?php echo date("Y-m-d h:i",strtotime($rows['created_at'])); ?>">
                <?php if(date("Y-m-d h:i",strtotime($rows['created_at'])) == date('Y-m-d h:i')) { ?>
                <span>Now</span>
                <?php }else{ ?>
                <span><?php echo date("h:i a",strtotime($rows['created_at']));  ?></span> <span>
                <?php if(date("Y-m-d",strtotime($rows['created_at'])) == date('Y-m-d')){echo "Today";}else{echo date("Y-m-d",strtotime($rows['created_at']));} ?>
                </span>
                <?php } ?>
              </time>
              <div id_toggle="{{$key}}" class="cbp_tmicon bg-success"><i class="entypo-doc-text"></i></div>
                <?php
					$note_type 	= 	isset($rows['NoteID'])?'NoteID':'ContactNote'; 
					$noteID		= 	isset($rows['NoteID'])?$rows['NoteID']:$rows['ContactNoteID'];
				?>
              <div class="cbp_tmlabel normal_tag"> 
               <a id="edit_note_{{$noteID}}" note-id="{{$noteID}}" note_type="{{$note_type}}"  key_id="{{$key}}" class="pull-right edit-deal edit_note_link"><i class="entypo-pencil"></i>&nbsp;</a>
            <a id="delete_note_{{$noteID}}" note-id="{{$noteID}}" note_type="{{$note_type}}"  key_id="{{$key}}" class="pull-right edit-deal delete_note_link"><i class="entypo-trash"></i></a>
                <h2 class="toggle_open" id_toggle="{{$key}}">@if($rows['CreatedBy']==$current_user_title) You @else {{$rows['CreatedBy']}}  @endif <span>added a note</span></h2>
                <div id="hidden-timeline-{{$key}}" class="details no-display">
                  <p>{{$rows['Note']}}</p>
                </div>
              </div>
            </li>
             @elseif(isset($rows['Timeline_type']) && $rows['Timeline_type']==Task::Ticket)
          <li id="timeline-{{$key}}" class="count-li timeline_ticket_entry">
            <time class="cbp_tmtime" datetime="<?php echo date("Y-m-d h:i",strtotime($rows['created_at'])); ?>">
              <?php if(date("Y-m-d h:i",strtotime($rows['created_at'])) == date('Y-m-d h:i')) { ?>
              <span>Now</span>
              <?php }else{ ?>
              <span><?php echo date("h:i a",strtotime($rows['created_at']));  ?></span> <span>
              <?php if(date("Y-m-d",strtotime($rows['created_at'])) == date('Y-m-d')){echo "Today";}else{echo date("Y-m-d",strtotime($rows['created_at']));} ?>
              </span>
              <?php } ?>
            </time>
            <div id_toggle="{{$key}}" class="cbp_tmicon bg-danger"><i class="entypo-ticket"></i></div>
            <div class="cbp_tmlabel normal_tag">  
              <h2 class="toggle_open" id_toggle="{{$key}}">Ticket<br><p>Subject: {{$rows['TicketSubject']}}</p></span></h2>
              <div id="hidden-timeline-{{$key}}" class="details no-display">
                <p>Status: {{$rows['TicketStatus']}}</p>
                <p>Requester: {{$rows['RequestEmail']}}</p>
                <p>Priority: {{$rows['TicketPriority']}}</p>
                <p>Type: {{$rows['TicketType']}}</p>
                <p>Group: {{$rows['TicketGroup']}}</p>
                <p>Date Created: {{$rows['created_at']}}</p>
                <p>Description: {{$rows['TicketDescription']}}</p>
                <p><a data_fetch_id="{{$rows['TicketID']}}" conversations_type="ticket" class="ticket_conversations">View Ticket Conversations</a></p>
              </div>
            </div>
          </li>
          @endif
            <?php $key++;  } ?>
            @endif
