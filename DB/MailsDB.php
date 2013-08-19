<?php

class MailsDB extends DB
{

	public function putmail($MAIL_TYPE, $vars)
	{
		$serialized_vars = serialize($vars);

		if($this->link)
		{
		$query = "insert into mail_queue (mail_type, vars) values ('$MAIL_TYPE', '$serialized_vars')";
		mysql_query($query);

		}

	}
	public function addMailLogs($rName,$rEmailId,$reason)
	{

		if($this->link)
		{
		$query = "insert into mail_logs (receiver_name,receiver_email_id,reason,add_time) values ('$rName', '$rEmailId','$reason',NOW())";
		mysql_query($query);

		}

	}
	function getTimePeriod($tsid){
		if($this->link)
                {
                $query = "SELECT DATE_FORMAT(st_date,'%D %M %Y'),DATE_FORMAT(end_date,'%D %M %Y')  from ts_interval where tsid=$tsid   ";
                $result = mysql_query($query);
                return $result;

                }

	}
	
	function getTimeInterval($end_date){
		if($this->link)
                {
                $query = "SELECT DATE_FORMAT(st_date,'%D %M %Y'),DATE_FORMAT(end_date,'%D %M %Y') from ts_interval where DATEDIFF(end_date,'$end_date')=0   ";
                $result = mysql_query($query);
                return $result;

                }

	}
	function getTimeSheetMissingApprovalRec($date,$cid){
		if($this->link)
                {
                $query = " SELECT email_id,first_name FROM users WHERE company_id =$cid AND users.id NOT  IN ( SELECT userid FROM ts_approvals, ts_interval WHERE ts_approvals.tsid = ts_interval.tsid AND DATEDIFF( ts_interval.end_date,  '$date' ) =0)      ";
                $result = mysql_query($query);
                return $result;

                }

	}
	function getTimeSheetAlertRecv($date,$cid){
		if($this->link)
		{
		$query = "SELECT email_id, first_name FROM users WHERE company_id =$cid AND users.id NOT  IN ( SELECT DISTINCT user_id FROM attendances WHERE start_date =  '$date')   ";
		$result = mysql_query($query);
		return $result;

		}
	}
	public function getmail($max=100)
	{
		if($this->link)
		{
		$query = "select mail_id, mail_type, vars from mail_queue limit 0,$max";
		$result = mysql_query($query);
		return $result;

		}

	}

	public function removemail($mail_id)
	{

		if($this->link)
		{
		$query = "delete from mail_queue where mail_id = $mail_id";
		$result = mysql_query($query);
		if(mysql_affected_rows() > 0)
		{
			return True;
		}
		return False;
		}

	}





	public function getActionReceivers($action_id, $uid)
	{
		if($this->link)
		{
			$query="select email_id, first_name from action_receivers, users where action_receivers.user_id = users.id and action_id=$action_id and users.id != $uid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}

	public function getThankReceivers($thank_id)
	{
		if($this->link)
		{
			$query="select email_id, first_name from thank_receivers, users where thank_receivers.user_id = users.id and thank_id=$thank_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}

	public function getBadgeName($badgeid)
	{
                if($this->link && $badgeid)
                {
			$query = "SELECT badgename FROM badges WHERE badgeid = $badgeid";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
				return $row[0];
                        }

                        return NULL;
                }
                return NULL;
	}




	public function getFeedReceivers($feed_id, $uid)
	{
		if($this->link)
		{
			$query="select users.id,email_id, first_name from feed_receivers, users where feed_receivers.user_id = users.id and feed_id=$feed_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}
	public function getFeedFrom($feed_id)
	{
		if($this->link)
		{
			$query="select users.id, email_id, first_name from feeds, users where feed_from = users.id and feeds.id=$feed_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}

	public function getFeedTo($feed_id, $uid)
	{
		if($this->link)
		{
			$query="select users.id, email_id, first_name from feeds, users where feed_to = users.id and feeds.id=$feed_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}
	public function getFeedCommenters($feed_id, $uid)
	{
		if($this->link)
		{
			$query="select users.id,email_id, first_name from feed_comments, users where comment_from = users.id and feed_id=$feed_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;


	}

	public function getManagers($userId,$compId ,$relation_type)
        {

                if($this -> link)
                {
                 /*       $query = "select distinct  id,first_name,last_name,image_src from  users where  id IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and company_id=$compId and activated=1 order by first_name";*/
                        $query = "select distinct  users.email_id,users.first_name  from  users ,user_connections WHERE users.id = user_connections.user_connection_id AND user_connections.user_id =$userId and company_id=$compId and  activated=1 and relation_type = $relation_type 
                                OR (users.id = user_connections.user_id AND user_connections.user_connection_id =$userId and company_id=$compId and  activated=1 and relation_type = (-1*$relation_type) ) 
order by first_name desc";
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }
	public function getCompanyId($uid)
        {
                if($this->link)
                {
                        $query="select company_id from users where id=$uid";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_row($result);
                                return $row[0];
                        }

                        return 0;
                }
                return 0;
        }
	
	public function getCompanies()
        {
                if($this->link)
                {
                        $query="select distinct id from companies";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				return $result;
                        }

                        return 0;
                }
                return 0;
        }

	public function getFullName($uid)
	{
		if($this->link)
		{
			$query="select CONCAT(first_name, ' ', last_name) from users where id=$uid ";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);
				return ucfirst($row[0]);
			}

			return 0;
		}
		return 0;
	}

	

	public function getCompanyName($uid)
	{
		if($this->link)
		{
			$query="select name from companies, users where users.company_id = companies.id and users.id=$uid";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);
				return $row[0];
			}

			return 0;
		}
		return 0;
	}

	public function getGoalName($goalid)
	{
		if($this->link)
		{
			$query="select name from goals where id = $goalid";
	$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);
				return $row[0];
			}

			return 0;
		}
		return 0;
	}


	public function userInfo($userid)
	{

		if($this -> link)
		{

			$query = "select email_id,first_name from users where id=$userid";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return mysql_fetch_row($result);
			}
			return 0;
		}
		return 0;
	}


	public function getInviteKey($uid, $email)
	{
                if($this->link)
                {
			$query = "SELECT ikey from invitations WHERE userid = $uid and invited = '$email'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
				return $row[0];
                        }

                        return NULL;
                }
                return NULL;
	}


}

?>
