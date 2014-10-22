SELECT message.message_id as mid, message.sent_by, message.subject, message.reply_to, message_recipient.read as r, message.timestamp, user.screenName, rt_subject, rt_sent_by, rt_screenName, rt_timestamp from
				message inner join message_recipient on message.message_id = message_recipient.message_id
				inner join user on sent_by=UserID
				left join (SELECT message_id, subject as rt_subject, timestamp as rt_timestamp, sent_by as rt_sent_by, screenName as rt_screenName FROM 
							message inner join user on sent_by = UserId) as rt
					on rt.message_id = message.reply_to
				where recipient=4 order by message.timestamp desc;