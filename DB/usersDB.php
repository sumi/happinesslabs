<?php
class usersDB extends DB
{
	public function isAdmin($user_id, $company_id) {
		if($this->link)
                {
                        $query="select level from domain_admin where userid=$user_id and companyid=$company_id";
                        $result = mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
			return 0;
                }
                return 0;
	}

	public function getUserReview($from,$to,$fid)
        {
                if($this->link)
                {
                        $query="select rating from user_reviews where review_from=$from and review_to=$to and field_id=$fid";
                        $result = mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
			return 0;
                }
                return 0;
        }


	public function addFeed($points,$feed_type,$feed_from,$feed_to,$content_text,$content_type,$content_id,$goal_id,$visibility_id,$more,$attached_type)
	{
		if($this->link)
		{

			$query="insert into feeds (points,feed_type,feed_from,feed_to,content_text,content_type,content_id,goal_id,visibility_type,more,last_update_time,attached_type)values($points,$feed_type,$feed_from,$feed_to,'$content_text',$content_type,$content_id,$goal_id,$visibility_id,$more,NOW(),$attached_type)";

			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				//$row=mysql_fetch_row($result);
				return mysql_insert_id();
			}
			return 0;
		}
		return 0;
	}


	public function addVisibility($feed_id,$type_name,$type_id)
	{
		if($this->link)
		{

			$query="insert into visibility_bits (feed_id,type_name,type_id)values($feed_id,$type_name,$type_id)";


			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				//$row=mysql_fetch_row($result);
				return mysql_insert_id();
			}
			return 0;
		}
		return 0;
	}

	public function checkUserReview($from,$to,$fid)
        {
                if($this->link)
                {
                        $query="select id from user_reviews where review_from=$from and review_to=$to and field_id=$fid";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                }
                return 0;
        }
	
	public function getUserReviewInput($from, $to, $fid) {
		if($this->link)
                {
                        $query="select user_comment from user_reviews_inputs where review_from=$from and review_to=$to and field_id=$fid";
                        $result = mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
			return "";
                }
                return "";
	}
	
	public function checkUserReviewInput($from,$to,$fid)
        {
                if($this->link)
                {
                        $query="select id from user_reviews_inputs where review_from=$from and review_to=$to and field_id=$fid";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                }
                return 0;
        }

	public function addUserReview($from,$to,$fid,$rating,$feedid)
        {
                if($this->link)
                {
                        $query="insert into user_reviews (review_from,review_to,field_id,rating,feed_id,add_time) values ($from,$to,$fid,$rating,$feedid,NOW())";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                }
                return 0;
        }
	
	public function updateUserReview($from,$to,$fid,$rating)
        {
                if($this->link)
                {
                        $query="update user_reviews set rating=$rating, update_time = NOW() where review_from=$from and review_to = $to and field_id = $fid";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                }
                return 0;
        }

	function addUserReviewInput($from,$to,$fid,$comment,$feedid)
        {
                if($this->link)
                {
                        $query="insert into user_reviews_inputs (review_from,review_to,field_id,user_comment,feed_id,created_at) values ($from,$to,$fid,'$comment',$feedid,NOW())";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return mysql_insert_id();
                        }
                }
                return 0;
        }
	
	function updateUserReviewInput($from,$to,$fid,$comment)
        {
                if($this->link)
                {
                        $query="update user_reviews_inputs set user_comment = '$comment', updated_at = NOW() where review_from = $from and review_to = $to and field_id = $fid";
                        mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                }
                return 0;
        }

	function getCompanyActiveProjects($cid){
                if($this->link)
                {
                        $query="select * from company_projects where deleted=0 and company_id=$cid order by title";
			$result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
			}
		}
		return "";
		
	}
	function getCompanyReviewForms($cid){
                if($this->link)
                {
                        $query="select id,description from review_forms where company_id=$cid and deleted is NULL";
			$result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
			}
		}
		return "";
		
	}

	function getProfileReviewForms($user_id)
	{
		if($this->link)
		{
			$query="select user_review_forms.review_form_id, review_forms.description from user_review_forms, review_forms where user_review_forms.user_id=$user_id and user_review_forms.review_form_id = review_forms.id and review_forms.deleted is NULL";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
		}
		return "";
	}
	
	function getFormName($fid){
                if($this->link)
                {
                        $query="select description from review_forms where id=$fid and deleted is NULL";
			$result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
			}
		}
		return "";
		
	}

	function getFormGroups($fid){
        if($this->link)
                {
                        $query="select id,group_name from review_form_groups where review_form_id=$fid and deleted is NULL";
			$result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
			}
		}
		return 0;
	}

	function getFormField($fid){
                if($this->link)
                {
                        $query="select id, group_id, description,weightage from review_form_fields where review_form_id=$fid and deleted is NULL";
			$result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
			}
		}
		return 0;
	}
	
	function getFormInput($fid)
	{
		if($this->link)
		{
			$query="select id,group_id,description,label from review_form_inputs where review_form_id=$fid and deleted is NULL";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
		}
		return "";

	}

	public function getUserEmail($user_id)
	{
		if($this->link)
		{
			$query="select email_id from users where id = $user_id";
			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}

		}
		return 0;
	}
	public function getApplications($user_id)
	{
		if($this->link)
		{
			$query="select * from social_applications where user_id = $user_id and deleted IS NULL ORDER BY created_at DESC";
			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}

		}
		return 0;
	}

	public function getCompanyProjects($company_id)
	{
		if($this->link)
		{
			$query="select * from company_projects where company_id = $company_id";
			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}

		}
		return 0;
	}
	
	function addUserActionPoints($cid,$groupid,$uid,$aid,$points){
		if($this->link){
			$query="insert into company_user_points(company_id,group_id,user_id,action_id,points,add_time)values($cid,$groupid,$uid,$aid,$points,NOW())";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() > 0){
				return 1;
			}
		}
		return 0;
	}
	
	function getEmployeeUserPoints($uid) {
		if($this->link){
			$query="select SUM(points) from company_user_points where user_id = $uid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() > 0){
				$row = mysql_fetch_row($result);
				if($row[0]) { return $row[0];} 
				else { return 0;}
			}
		}
		return 0;
	}
	
	function getEmployeeBidPoints($uid){
		if($this->link){
			$query="select SUM(points) from company_gift_bids where user_id = $uid and deleted is NULL";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() > 0){
				$row = mysql_fetch_row($result);
				if($row[0]) { return $row[0];} 
				else { return 0;}
			}
		}
		return 0;
	}


	
	//public function getCompanyId($id)
	//{
	//	if($this->link)
	//	{
	//		$query="select company_id from users where id = $id";
	//		$result = mysql_query($query , $this->link);
	//		if(mysql_affected_rows()>0)
	//		{
	//			$company_result = mysql_fetch_row($result);
	//			return $company_result[0];
	//		}

	//	}
	//	return 0;
	//}
	public function has_paid($id, $module_id)
	{
		if($this->link)
		{
			$query="select company_id from users where id = $id";
			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				$company_result = mysql_fetch_row($result);
				$company_id = $company_result[0];
				$query="select * from module_payments where module_id = $module_id and company_id = $company_id and start_time <= NOW() and end_time >= NOW()";
				$result = mysql_query($query , $this->link);
				if(mysql_affected_rows()>0)
				{
					return 1;
				}
			}

		}
		return 0;
	}
	public function canGetAttendance($id, $user_id)
	{
		if($this->link)
		{
			//check id user_id is admin
			$query="select * from domain_admin where userid = $user_id";

			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			else
			{
				return 1;
				//check id user_id is manager of id
				//$query="select * from user_connections where user_id = $user_id and user_connection_id = $id and relation_type = 1";
				//$result = mysql_query($query , $this->link);
				//if(mysql_affected_rows()>0)
				//{
				//	return 1;
				//}
			}

		}
		return 0;
	}
	public function getApprovalAttendances($id)
	{
		if($this->link)
		{
			$query="select * from attendances where deleted_on IS NULL and user_id = $id and approved IS NULL ORDER BY start_date ASC LIMIT 1";

			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				$last_unapproved_date = $row[1];
				$two_weeks_after = date("Y-m-d",strtotime(date("Y-m-d", strtotime($last_unapproved_date)) . " +2 week"));
				
				$query="select * from attendances where deleted_on IS NULL and user_id = $id and approved IS NULL and start_date <= '$two_weeks_after' and start_date >= '$last_unapproved_date' ORDER BY start_date ASC";
				$result = mysql_query($query , $this->link);
				if(mysql_affected_rows()>0)
				{
					return $result;
				}
				
			}

			return 0;
		}
		return 0;
	}
	public function setApprovalAttendances($id)
	{
		if($this->link)
		{
			$query="select * from attendances where deleted_on IS NULL and user_id = $id and approved IS NULL ORDER BY start_date ASC LIMIT 1";

			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				$last_unapproved_date = $row[1];
				$two_weeks_after = date("Y-m-d",strtotime(date("Y-m-d", strtotime($last_unapproved_date)) . " +2 week"));
				
				$query="update attendances set approved=1 where deleted_on IS NULL and user_id = $id and start_date <= '$two_weeks_after' and start_date >= '$last_unapproved_date'";
				$result = mysql_query($query , $this->link);
				if(mysql_affected_rows()>0)
				{
					return 1;
				}
				return 2;
				
			}

			return 3;
		}
		return 0;
	}
	public function getAttendances($id, $from, $to)
	{
		if($this->link)
		{
			if((!empty($from)) && (!empty($to)))
			{
				$query="select attendances.id,DATE_FORMAT(attendances.start_date, '%D,%M %Y'),TIME_FORMAT(attendances.start_time,'%h:%i %p'),TIME_FORMAT(attendances.end_time,'%h:%i %p'),attendances.user_id,attendances.created_at,attendances.updated_at,attendances.deleted_on,attendances.note,company_projects.id,company_projects.title,attendances.approved from attendances,company_projects  where attendances.project_id=company_projects.id and  deleted_on IS NULL and user_id = $id and start_date >= $from and start_date <= $to ORDER BY start_date DESC, start_time ASC, end_time ASC";
			}
			elseif(empty($from) && (!empty($to)))
			{
				$query="select  attendances.id,DATE_FORMAT(attendances.start_date, '%D,%M %Y'),TIME_FORMAT(attendances.start_time,'%h:%i %p'),TIME_FORMAT(attendances.end_time,'%h:%i %p'), attendances.user_id,attendances.created_at,attendances.updated_at,attendances.deleted_on,attendances.note,company_projects.id,company_projects.title,attendances.approved from attendances,company_projects where attendances.project_id=company_projects.id and deleted_on IS NULL and user_id = $id and start_date <= $to ORDER BY start_date DESC, start_time ASC, end_time ASC";
			}
			elseif((!empty($from)) && empty($to))
			{
				$query="select attendances.id,DATE_FORMAT(attendances.start_date, '%D,%M %Y'),TIME_FORMAT(attendances.start_time,'%h:%i %p'),TIME_FORMAT(attendances.end_time,'%h:%i %p'),attendances.user_id,attendances.created_at,attendances.updated_at,attendances.deleted_on,attendances.note,company_projects.id,company_projects.title,attendances.approved from attendances,company_projects where  attendances.project_id=company_projects.id and deleted_on IS NULL and user_id = $id and start_date >= $from ORDER BY start_date DESC, start_time ASC, end_time ASC";
			}
			else
			{
				$query="select  attendances.id,DATE_FORMAT(attendances.start_date,'%D %M %Y'),TIME_FORMAT(attendances.start_time,'%h:%i %p'),TIME_FORMAT(attendances.end_time,'%h:%i %p'),attendances.user_id,attendances.created_at,attendances.updated_at,attendances.deleted_on,attendances.note,company_projects.id,company_projects.title,attendances.approved from attendances,company_projects,ts_interval where attendances.project_id=company_projects.id and  deleted_on IS NULL and user_id = $id and attendances.start_date >= ts_interval.st_date and attendances.start_date <= ts_interval.end_date and ts_interval.tsid NOT IN (select tsid from ts_approvals where userid=$id) ORDER BY attendances.start_date DESC, start_time ASC, end_time ASC";
			}
			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}

			return 0;
		}
		return 0;
	}
	public function CanUpdateAttendance($id, $user_id)
	{
		if($this->link)
		{
			$query="select * from attendances where deleted_on IS NULL and user_id = $user_id and id = $id";

			$result = mysql_query($query , $this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}

			return 0;
		}
		return 0;
	}
	public function getAutoUser($name, $cid)
	{
		if($this->link)
		{
			$query="select id,CONCAT(first_name,' ', last_name) from users where company_id = $cid and activated = 1 and (CONCAT(first_name, ' ', last_name) like '$name%' or first_name like '$name%' or last_name like '$name%') limit 0,10";

			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}

			return 0;
		}
		return 0;
	}
	

	 public function getGroupUser($name,$group_id)
        {
                if($this->link)
                {
                        $query="select users.id,CONCAT(first_name,' ', last_name) from users,user_groups where user_groups.group_id = $group_id and user_groups.unjoined_at = '0000-00-00' and user_groups.user_id = users.id  and (CONCAT(first_name, ' ', last_name) like '$name%' or first_name like '$name%' or last_name like '$name%') limit 0,10";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }

                        return 0;
                }
                return 0;
        }
	
	public function getAutoUserEmail($name)
	{
		if($this->link)
		{
			$query="select email_id from users where email_id like '$name%'  limit 0,10";

			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}

			return 0;
		}
		return 0;
	}

	public function getBio($id)
	{
		if($this->link)
		{
			$query="select bio from market_users  where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
		}
		return 0;
	}



	public function updateConnection($userId,$compId)
        {

                if($this -> link)
                {
                        $query = "select distinct  id from  users where  id IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and company_id=$compId and activated=1";
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }

	public function nullPassword($uid)
	{
		if($this->link)
		{
			$query="select id from users where id=$uid and password = ''";
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

	public function deactivatingUser($today)
	{
		if($this->link)
		{
			$query="update  users set activated=0 where DATEDIFF('$today', deactivate_time) = 0";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}

			return 0;
		}
		return 0;
	}
	public function activateUser($uid)
	{
		if($this->link)
		{
			$query="update  users set activated=1,deactivate_time='0000-00-00' where id =$uid ";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}

			return 0;
		}
		return 0;
	}

	public function addPassword($uid, $password)
	{
		if($this->link)
		{
			$query="update users set password = '$password' where id=$uid";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}

			return 0;
		}
		return 0;
	}



	public function getGender($uid)
	{
		if($this->link)
		{
			$query="select gender from users where id=$uid";

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

	public function firstName($uid)
	{
		if($this->link)
		{
			$query="select first_name from users where id=$uid";

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
	
	public function fullName($uid,$cid=null)
	{
		if($this->link)
		{
			$query="select CONCAT(first_name, ' ', last_name) from users where id=$uid ";
			if($cid != null)
				$query .= " and company_id = $cid";
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

/* User-Goal related queries here */

	public function getPGoalsByUserId($user_id, $offset=0, $limit = 5)
        {
                if($this -> link)
                {
              $query = "SELECT personal_goals.id, personal_goals.name,personal_goals.image_src,DATE_FORMAT(due_date,'%D %M %Y'),progress_types.name
                          FROM personal_goals,progress_types WHERE personal_goals.created_by = $user_id and personal_goals.end_date = '0000-00-00' and progress_types.id=personal_goals.progress_id order by personal_goals.id desc limit $offset,$limit";

                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }
	public function getNumPGoalsByUserId($user_id)
        {
                if($this -> link)
                {
              $query = "SELECT count(personal_goals.id)  FROM personal_goals,progress_types WHERE personal_goals.created_by = $user_id and  personal_goals.end_date = '0000-00-00' and progress_types.id=personal_goals.progress_id";

                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
                        return 0;
                }
                return 0;
        }


	public function getPGoalsSupportedByUserId($user_id, $offset=0, $limit = 5)
        {
                if($this -> link)
                {
              $query = "SELECT pg.id, pg.name, pg.image_src, DATE_FORMAT(due_date,'%D %M %Y'),progress_types.name,users.id,users.image_src   FROM personal_goals as pg,personal_goal_contributors as pgc, progress_types, users WHERE pgc.user_id = $user_id and pgc.flag =1 and pgc.goal_id = pg.id and  pg.end_date = '0000-00-00' and pg.created_by != $user_id and progress_types.id=pg.progress_id and pg.created_by = users.id  order by pg.id desc limit $offset, $limit"; 

                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }
	public function getNumPGoalsSupportedByUserId($user_id)
        {
                if($this -> link)
                {
              $query = "SELECT count(pg.id) FROM personal_goals as pg,personal_goal_contributors as pgc, progress_types WHERE pgc.user_id = $user_id and pgc.flag =1 and pgc.goal_id = pg.id and pg.created_by != $user_id and pg.end_date = '0000-00-00'  and progress_types.id=pg.progress_id"; 

                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
                        return 0;
                }
                return 0;
        }

	public function getPGoalContributor($user_id, $sumflag, $offset = 0, $limit =5)
        {
                if($this -> link)
                {
                        $query = "select users.id,users.first_name,users.last_name,users.image_src,SUM(flag)  from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goal_contributors.user_id=users.id and personal_goals.created_by = $user_id and personal_goal_contributors.user_id != $user_id group by users.id having SUM(flag) >= $sumflag order by SUM(flag) desc limit $offset,$limit";
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }

	public function getNumPGoalContributor($user_id, $sumflag)
        {
                if($this -> link)
                {
                        $query = "select users.id from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goal_contributors.user_id=users.id and personal_goals.created_by = $user_id and personal_goal_contributors.user_id != $user_id group by users.id  having SUM(flag) >= $sumflag";
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
				return mysql_affected_rows();
                        }
                        return 0;
                }
                return 0;
        }
	public function getPGoalContributed($user_id, $offset = 0, $limit =5)
        {
                if($this -> link)
                {
                        $query = "select distinct users.id,users.first_name,users.last_name,users.image_src  from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goals.created_by = users.id and personal_goal_contributors.user_id=$user_id and personal_goal_contributors.flag =1 and personal_goals.created_by != $user_id order by RAND() limit $offset, $limit"; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }
	public function getIdPGoalContributed($user_id)
        {
                if($this -> link)
                {
                        $query = "select distinct users.id  from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goals.created_by = users.id and personal_goal_contributors.user_id=$user_id and personal_goal_contributors.flag =1 and personal_goals.created_by != $user_id "; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;
        }


	public function getNumPGoalContributed($user_id)
        {
                if($this -> link)
                {
                        $query = "select count(distinct users.id) from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goals.created_by = users.id and personal_goal_contributors.user_id=$user_id and personal_goal_contributors.flag =1 and personal_goals.created_by != $user_id"; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
                        return 0;
                }
                return 0;
        }

	public function ifrelated($user1, $user2)
        {
                if($this -> link)
                {
                        $query = "select distinct users.id from users,personal_goal_contributors,personal_goals where personal_goal_contributors.goal_id = personal_goals.id and  personal_goals.created_by = users.id and personal_goal_contributors.flag =1 and ((personal_goals.created_by = $user2 and personal_goal_contributors.user_id=$user1) OR  (personal_goals.created_by = $user1 and personal_goal_contributors.user_id=$user2)) "; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return true;
                        }
                        return false;
                }
                return false;
        }



	

	public function pendingRequest($user_id, $offset=0, $limit=6)
	{

	   if($this -> link)
                {
                        $query = "SELECT  pg.id, pg.name, pg.image_src,users.id, CONCAT(users.first_name,' ',users.last_name) as uname FROM `personal_goal_contributors` as pgc, personal_goals as pg,users WHERE pgc.user_id = $user_id and flag = 0 and pgc.goal_id = pg.id and users.id = pg.created_by order by pgc.add_time desc limit $offset, $limit"; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }
                        return 0;
                }
                return 0;

	}
	public function numPendingRequest($user_id)
	{

	   if($this -> link)
                {
                        $query = "SELECT  count(pg.id) FROM `personal_goal_contributors` as pgc, personal_goals as pg,users WHERE pgc.user_id = $user_id and flag = 0 and pgc.goal_id = pg.id and users.id = pg.created_by "; 
                        $result=mysql_query($query,$this->link);

                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row[0];
                        }
                        return 0;
                }
                return 0;

	}



/* User-Deal related queries */
public function getDeals($USERID, $offset=0, $limit=5)
{
		if($this -> link)
		{
			$query = "select dealid, title, image_src, cost, currency, DATE_FORMAT(start_date, '%D %M %Y') from deals where created_by=$USERID and removed=0 order by expired, dealid desc limit $offset, $limit";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
}

public function getNumDeals($USERID)
{
		if($this -> link)
		{
			$query = "select count(dealid)  from deals where created_by=$USERID and removed = 0 ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
                                return $row[0];
			}
			return 0;
		}
		return 0;
}


public function getMyBuyers($userid, $offset=0, $limit=5)
{
		if($this -> link)
		{
			$query = "select distinct users.id, users.first_name, users.last_name, users.image_src from deals,personal_goal_deals as pgd,personal_goals as pg,users where pgd.goal_id = pg.id and pgd.deal_id = deals.dealid and pgd.end_date = '0000-00-00' and pg.end_date = '0000-00-00' and pg.created_by = users.id and deals.created_by = $userid  and deals.end_date = '0000-00-00' order by RAND() limit $offset, $limit";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
}

public function getNumMyBuyers($userid)
{
		if($this -> link)
		{
			$query = "select count(distinct users.id) from deals,personal_goal_deals as pgd,personal_goals as pg,users where pgd.goal_id = pg.id and pgd.deal_id = deals.dealid and pgd.end_date = '0000-00-00' and pg.end_date = '0000-00-00' and  pg.created_by = users.id and deals.created_by = $userid and deals.end_date ='0000-00-00'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
                                return $row[0];
			}
			return 0;
		}
		return 0;
}





/*......*/




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
	public function getCompanyActionPoints($cid){
		if($this->link){
			$query="select company_actions.id,company_actions.action,points from company_actions,company_action_points where company_actions.id=company_action_points.action_id and company_id=$cid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() >0){
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function getActionPoints($aid,$cid){
		if($this->link){
			$query="select points from company_action_points where action_id=$aid and company_id=$cid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() > 0){
				$row=mysql_fetch_row($result);
				return $row[0];
			}
		}
		else return 0;
	}
	public function updateActionPoints($aid,$cid,$points){
		if($this->link){
			$query="update company_action_points set points=$points where action_id=$aid and company_id=$cid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows() > 0){
				return 1;
			}
		}
		return 0;
	}
		 public function getCompanyFrequency($id)
        {
                if($this->link)
                {
                        $query="select frequency_badge_points from company_badges where company_id=$id";
                        $result = mysql_query($query , $this -> link);
			$frequency=0;
                        while($row=mysql_fetch_row($result))
                        {
				$frequency=$row[0];
                        }
                                return $frequency;

                }
                return 0;
        }
	 public function getCompanyStartDate($id)
        {
                if($this->link)
                {
                        $query="select start_date from company_badges where company_id=$id";
                        $result = mysql_query($query , $this -> link);
                        $frequency=0;
                        while($row=mysql_fetch_row($result))
                        {
                                $frequency=$row[0];
                        }
                                return $frequency;

                }
                return 0;
        }
	
	 public function getCompanyEndDate($id)
        {
                if($this->link)
                {
                        $query="select end_date from company_badges where company_id=$id";
                        $result = mysql_query($query , $this -> link);
                        $frequency=0;
                        while($row=mysql_fetch_row($result))
                        {
                                $frequency=$row[0];
                        }
                                return $frequency;

                }
                return 0;
        }



	 public function getUpdateBit($id)
        {
                if($this->link)
                {
                        $query="select update_bit,id from company_badges where company_id=$id";
                        $result = mysql_query($query , $this -> link);
                        $update_bit=0;
			if(mysql_affected_rows()>0){
				return $result;
			}

                }
                return 0;
        }
	 public function getUserRemainingPoint($userId)
        {
                if($this->link)
                {
                        $query="select remaining_badge_points from users where id=$userId";
                        $result = mysql_query($query , $this -> link);
                        $update_bit=0;
                        if(mysql_affected_rows()>0){
                               	$row=mysql_fetch_row($result);
				return $row[0];
                        }
			return 0;

                }
                return 0;
        }
	 public function getUserTotalPoint($userId)
        {
                if($this->link)
                {
                        $query="select badge_points from users where id=$userId";
                        $result = mysql_query($query , $this -> link);
                        $update_bit=0;
                        if(mysql_affected_rows()>0){
                                $row=mysql_fetch_row($result);
                                return $row[0];
                        }
                        return 0;

                }
                return 0;
        }

	public function getCompanyBadgeGivingStartDate($id)
        {
                if($this->link)
                {
                        $query="select DATE_FORMAT(start_date,'%D %M %Y') from company_badges where company_id=$id";
                        $result = mysql_query($query , $this -> link);
                        $start_date=0;
                        while($row=mysql_fetch_row($result))
                        {
                                $start_date=$row[0];
                        }
                                return $start_date;

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
	public function getCompanyImageFromUID($uid)
	{
		if($this->link)
		{
			$query="select companies.image_src from companies, users where users.company_id = companies.id and users.id=$uid";
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
	public function getCompanyAddress($uid)
	{
		if($this->link)
		{
			$query="select address from companies, users where users.company_id = companies.id and users.id=$uid";

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
	public function getCompanyDomain($uid)
	{
		if($this->link)
		{
			$query="select domain from companies, users where users.company_id = companies.id and users.id=$uid";

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


	public function getImage($id)
	{
		if($this->link)
		{
			$query = "select image_src from users where id='$id'";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_array($result);
				return $row['image_src'];
			}

			return 0;
		}
		return 0;
	}
	public function insertMBTI($userId,$score_type)
	{
		if($this->link)
		{
			$query = "update users set MBTIScore='$score_type'  where id=$userId";


			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}

	}
	 public function updateUpdateBit($id)
        {
                if($this->link)
                {
                        $query = "update company_badges set update_bit=1  where id=$id";


                        $result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                        return 0;
                }

        }

	 public function updateBadgePoints($companyId)
        {
                if($this->link)
                {
                        $query = "update users set remaining_badge_points=badge_points  where company_id=$companyId";


                        $result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                        return 0;
                }

        }
/* public function reduceUserRemainingPoint($companyId)
        {
                if($this->link)
                {
                        $query = "update users set remaining_badge_points=badge_points  where company_id=$companyId";


                        $result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                        return 0;
                }

        }
*/
	public function getMBTI($userId)
	{
		if($this->link)
		{
			$query="select MBTIScore from users  where id=$userId";
			$result=mysql_query($query,$this->link);

			$num_rows = mysql_num_rows($result);
			//$num_rows = $num_rows-1;
			if($num_rows > 0)
			{
				$row=mysql_fetch_row($result);
				return $row[0];
			}			
			return 0;
		} 
		return 0;
	}
	public function ifuserliked($like_from_id,$sent_id,$like_to_id)
	{
		if($this->link)
		{
			$query="select id from mbti_likes where sent_id='$sent_id' and like_to_id=$like_to_id and like_from_id = $like_from_id";
			$result = mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		} 
		return 0;

	}

	function deleteComment($comment_id)
	{
		if($this->link)
		{
			$query = "delete from mbti_comment where id = $comment_id ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function removeManager($uid,$mid)
	{
		if($this->link)
		{
			$query = "delete from user_connections where user_id = $uid and user_connection_id=$mid and relation_type=-1 ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
		//		return 1;
			}
			$query = "delete from user_connections where user_id = $mid and user_connection_id=$uid and relation_type=1 ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
	public function insertlike($userId,$profileid,$sentid)
	{
		if($this->link)
		{
			$query="insert into mbti_likes (like_to_id,like_from_id,sent_id) values ($profileid,$userId,'$sentid')";
			mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
		} 
		return 0;
	}
	public function getLikes($sentid,$like_to)
	{
		if($this->link)
		{
			$query="select like_from_id, first_name,mbti_likes.id from mbti_likes,users where sent_id='$sentid' and like_to_id=$like_to and users.id = like_from_id";
			$result = mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
		} 
		return 0;
	}
	public function getComments($profile_id,$sentid)
	{
		if($this->link)
		{
			$query="select mbti_comment.id, first_name,comment_from,comments as comment_text from mbti_comment,users where sent_id='$sentid' and comment_to=$profile_id and users.id = comment_from";
			$result = mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
		} 
		return 0;
	}
	public function insertcomment($type,$comment_from,$comment_to,$sentid,$body)
	{
		$query = "insert into mbti_comment (comment_type ,comment_from,comment_to,sent_id,comments) values ('$type',$comment_from,$comment_to,'$sentid','$body')";
		mysql_query($query,$this -> link);
		if(mysql_affected_rows()>0)
		{
			$query = "SELECT LAST_INSERT_ID() from mbti_comment";
			$result = mysql_query($query);
			$row = mysql_fetch_row($result);
			$last_id = $row[0];
			return $last_id;
		}
		return 0;
	}
	public function getMBTIContent($reltype,$mbtiprop)
	{
		if($this->link)
		{
			$query="select id,content,order_num  from mbti_rel  where rel_type='$reltype' and mbti_prop='$mbtiprop' order by order_num";
			$result=mysql_query($query,$this->link);

			return $result;
		} 
		return 0;
	}
	public function updateCompanyImage($image,$id)
	{
		if($this->link)
		{
			$query="update companies set image_src='$image' where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

	public function getCompanyImage($company_id)
	{
		if($this->link)
		{
			$query="select companies.image_src from companies where id=$company_id";
			$result = mysql_query($query , $this -> link);
			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_array($result);
				return $row['image_src'];
			}

			return 0;
		}
		return 0;
	}

	public function SaveConnections($userId,$peoples,$relation_bit)
	{
		if(!$relation_bit)
			$relation_bit = 0;
		if($this -> link)
		{

			$pids= explode(",", $peoples);
			if(count($pids))
			{
				$query="insert into user_connections (user_id,user_connection_id,relation_type) values ";
				$people = array();
				foreach($pids as $pid)
				{
					$people[] = " ($userId, $pid ,$relation_bit) ";
			//		$relation_bit = -1*$relation_bit;
			//		$people[] = " ($pid, $userId ,$relation_bit) ";
				}

				$dquery = implode(",", $people);

				$query= $query.$dquery;
				$result=mysql_query($query,$this->link);
				if(mysql_affected_rows()>0)
				{
					return 1;
				}
				return 0;
			}

		}
	}
	public function SaveOneConnection($userId,$people)
	{

		if($this -> link)
		{

			$query="insert into user_connections (user_id,user_connection_id) values ($userId,$people), ($people, $userId)";

			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;

		}
	}
	public function RemoveOneConnection($userId,$people,$relation_type)
	{

		if($this -> link)
		{
			$query = "delete from user_connections where ((user_id=$userId and user_connection_id=$people) OR (user_id=$people and user_connection_id = $userId)) and (relation_type = $relation_type or relation_type = (-1 * $relation_type)) ";

			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;

		}
	}
	public function GetConnections($userId)
	{

		if($this -> link)
		{

			$query = "select distinct  id,first_name,last_name,image_src from users where id=$userId and activated=1";

			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}

	public function NumOfCompConn($compId)
	{
		if($this -> link)
		{
			$query = "select count(*) from  users where company_id=$compId and activated=1";

			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);

				return $row[0];
			}
			return 0;
		}
		return 0;

	}
	public function isCompanyUser($companyId,$userId)
	{
		if($this -> link)
		{
			$query = "select * from users where users.company_id=$companyId and users.id=$userId";

			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{

				return 1;
			}
			return 0;
		}
		return 0;

	}

	public function NumOfConn($userId,$compId,$relation_type)
	{
		if($this -> link)
		{
/*			$query = "select count(*) from  users where  id IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and company_id=$compId and activated=1 ";*/
			$query = "select count(distinct users.id) from  users ,user_connections WHERE users.id = user_connections.user_connection_id AND user_connections.user_id =$userId and company_id=$compId and  activated=1 and relation_type = $relation_type 
				OR (users.id = user_connections.user_id AND user_connections.user_connection_id =$userId and company_id=$compId and  activated=1 and relation_type = (-1*$relation_type) ) ";

			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);

				return $row[0];
			}
			return 0;
		}
		return 0;

	}
	public function ShowAllConnectionsUser($userId,$limit,$compId,$relation_type)
	{

		if($this -> link)
		{
/*			$query = "select distinct  id,first_name,last_name,image_src from  users where  id IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and company_id=$compId and  activated=1 order by RAND() limit $limit, ".($limit+8) ;*/
			$query = "select distinct  users.id,first_name,last_name,image_src from  users ,user_connections 
				WHERE (users.id = user_connections.user_connection_id AND user_connections.user_id =$userId and company_id=$compId and  activated=1 and relation_type = $relation_type ) 
				OR (users.id = user_connections.user_id AND user_connections.user_connection_id =$userId and company_id=$compId and  activated=1 and relation_type = (-1*$relation_type) ) 
				order by relation_type,RAND() limit $limit, ".($limit+9) ;
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	 public function showUserConnection($userId,$compId ,$relation_type)
        {

                if($this -> link)
                {
                 /*       $query = "select distinct  id,first_name,last_name,image_src from  users where  id IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and company_id=$compId and activated=1 order by first_name";*/
			$query = "select distinct  users.id,first_name,last_name,image_src  from  users ,user_connections WHERE users.id = user_connections.user_connection_id AND user_connections.user_id =$userId and company_id=$compId and  activated=1 and relation_type = $relation_type 
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
		public function getDirectReports($company_id ,$user_id)
		{
			if($this -> link)
			{
			$query = "select distinct  users.id, first_name, last_name from  users ,user_connections WHERE users.company_id=$company_id and  users.activated=1 AND ((users.id = user_connections.user_connection_id and  user_connections.user_id = $user_id  and user_connections.relation_type = 1) OR (users.id = user_connections.user_id  and user_connections.user_connection_id = $user_id and user_connections.relation_type = -1)) order by first_name";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
			}

		}
	
		public function getUserDepartment($user_id, $company_id)
		{
			if($this -> link)
			{

			$query = "select department from  users WHERE users.id = $user_id and company_id=$company_id";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
			}

		}


	public function NumOfHavingMeInConn($userId,$compId)
	{
		if($this -> link)
		{
			$query = "SELECT count(*) FROM users WHERE id IN ( SELECT DISTINCT user_connections.user_id FROM users, user_connections WHERE users.id = user_connections.user_id AND user_connections.user_connection_id =$userId ) AND company_id =$compId AND activated =1 ORDER BY first_name, last_name";

			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$row=mysql_fetch_row($result);

				return $row[0];
			}
			return 0;
		}
		return 0;

	}
	public function HavingMeInConnections($userId,$limit,$compId)
	{

		if($this -> link)
		{
			$query = "SELECT DISTINCT id, first_name, last_name, image_src FROM users WHERE id IN ( SELECT DISTINCT user_connections.user_id FROM users, user_connections WHERE users.id = user_connections.user_id AND user_connections.user_connection_id =$userId ) AND company_id =$compId AND activated =1 ORDER BY  RAND() limit $limit, ".($limit+8);
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function GetCompconnections($compId,$limit)
	{
	$limit= 0;

		if($this -> link)
		{
			$query = "select distinct id,first_name,last_name,image_src,deactivate_time from users where company_id=$compId and activated=1 ORDER BY RAND() limit 0, 10";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	
	public function GetReviewconnections($compId,$review_id)
	{
	$limit= 0;

		if($this -> link)
		{
			$query = "select distinct id,first_name,last_name,image_src,deactivate_time from users where company_id=$compId and activated=1 and id NOT IN ( select user_id from user_review_forms where review_form_id = $review_id ) ORDER BY first_name, last_name ";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	
	public function GetAddedReviewconnections($compId,$review_id)
	{
	$limit= 0;

		if($this -> link)
		{
			$query = "select distinct id,first_name,last_name,image_src,deactivate_time from users where company_id=$compId and activated=1 and id IN ( select user_id from user_review_forms where review_form_id = $review_id ) ORDER BY  first_name, last_name ";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function getAllUsers($compId)
	{
		if($this -> link)
		{
			$query = "select id, first_name, last_name from users where company_id=$compId and activated=1 ORDER BY first_name";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
		public function getManagerReporters($company_id ,$user_id)
		{
		if($this -> link)
		{
		$query = "select distinct  users.id,first_name,last_name,image_src,gender from  users ,user_connections WHERE users.id = user_connections.user_connection_id AND user_connections.user_id =$user_id and company_id=$company_id and  activated=1 and relation_type = 1 order by first_name ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
		}

	public function isManager($company_id, $user_id)
	{
		if($this->link)
		{
			$query = "select count(distinct  users.id) from  users ,user_connections WHERE users.company_id= $company_id and  users.activated=1 AND ((users.id = user_connections.user_connection_id and  user_connections.user_id = $user_id  and user_connections.relation_type = 1) OR (users.id = user_connections.user_id  and user_connections.user_connection_id = $user_id and user_connections.relation_type = -1))";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
		}
		return 0;

	}

	public function TotalActCompconnections($compId,$limit)
	{
	$limit= 0;

		if($this -> link)
		{
			$query = "select distinct id,first_name,last_name,image_src,deactivate_time from users where company_id=$compId and activated=1 ORDER BY first_name";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function TotalCompconnections($compId,$limit)
	{
	$limit= 0;

		if($this -> link)
		{
			$query = "select  DISTINCT users.id,users.first_name,users.last_name,users.image_src,users.activated,users_info.salary,users_info.joining_date,users.deactivate_time,users.department from users LEFT JOIN users_info ON users.id=users_info.user_id where company_id=$compId   ORDER BY first_name";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}

	public function ShowCompconnections($compId,$limit)
	{

		if($this -> link)
		{
			$query = "select distinct id,first_name,last_name,image_src,badge_points,remaining_badge_points from users where company_id=$compId and activated=1";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	public function NumCompconnections($compId)
	{

		if($this -> link)
		{
			$query = "select count(id) from users where company_id=$compId and activated=1";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
			$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
		}
		return 0;
	}
	 public function saveUserBadgePoints($userId,$points)
        {
        if($this->link)
        {
                $query="update users set badge_points=$points where id=$userId ";
                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
                        return 1;
                }
                return 0;
        }
        return 0;
        }
	 public function updateUserRemainingBadgePoints($userId,$points)
        {
        if($this->link)
        {
                $query="update users set remaining_badge_points=$points where id=$userId ";
                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
                        return 1;
                }
                return 0;
        }
        return 0;
        }

	 public function saveCompanyFrequency($compId,$startDate,$endDate)
        {
        if($this->link)
        {
//                $query="update companies set frequency_badge_points=$points where id=$compId ";
		$query="insert into company_badges (company_id,start_date,end_date,update_bit) values ($compId,'$startDate','$endDate',0)";

                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
                        return 1;
                }
                return 0;
        }
        return 0;
        }

	public function ShowAllConnections($userId,$compId , $conn_type)
	{
		if($this -> link)
		{
		if($conn_type == 'people')	
		{
			$query = "select distinct  id,first_name,last_name,image_src from  users where  id NOT IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId and relation_type = 0) 
and id NOT IN 
(SELECT  distinct user_connections.user_id FROM users, user_connections WHERE users.id = user_connections.user_connection_id AND users.id =$userId and (relation_type = 0)) 



and users.id != $userId and company_id=$compId and activated=1 order by rand()";
		}
		else if($conn_type == 'reporters' || $conn_type == 'managers')
		{
			$query = "select distinct  id,first_name,last_name,image_src from  users where  id NOT IN 

(SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId and (relation_type = 1 or relation_type = -1)) 

and id NOT IN 
(SELECT  distinct user_connections.user_id FROM users, user_connections WHERE users.id = user_connections.user_connection_id AND users.id =$userId and (relation_type = 1 or relation_type = -1)) 

 and users.id != $userId and company_id=$compId and activated=1 order by rand()";

		}
		else
		{
			$query = "select distinct  id,first_name,last_name,image_src from  users where  id NOT IN (SELECT  distinct user_connections.user_connection_id FROM users, user_connections WHERE users.id = user_connections.user_id AND users.id =$userId) and users.id != $userId and company_id=$compId and activated=1 order by rand()";
		}
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	}
	function isConnected($user_id,$id)
	{
		if($this -> link)
		{
			$query = "select distinct  id from  user_connections WHERE (user_id = $user_id AND user_connection_id=$id) OR (user_id = $id and user_connection_id = $user_id)";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

	public function get_matching_mbti($userID, $connection_mbti_value, $connectionID)
	{
		$same_mbti_connections=array(); 
		if ($this->link) 
		{	$query="SELECT id, CONCAT(first_name,' ', last_name) as name, image_src, MBTIScore from users where id IN (SELECT user_connection_id FROM user_connections where user_id=".$userID.") and MBTIScore='".$connection_mbti_value."' and id!=".$connectionID; 
			$result=mysql_query($query,$this->link); 
			if (mysql_affected_rows()>0) 
			{	while ($row=mysql_fetch_array($result)) 
				//$same_mbti_connections[]=$row['first_name'];  
				$same_mbti_connections[]=$row; 	

			} 
		} 
		return $same_mbti_connections; 
	}

function getTitle($user_id)
{
		if($this -> link)
		{
			$query = "select title from users where id = $user_id ";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	
}
function getDepartment($user_id)
{
		if($this -> link)
		{
			$query = "select department from users where id = $user_id ";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
	
}


function getUserType($user_id)
{
		if($this -> link)
		{
			$query = "select user_type from users where id = $user_id ";
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
		}
		return 0;
	
}

	function updateEmployeeInfo($uid,$salary,$joindate){
		if($this -> link)
		{
			$query="update users_info set salary=$salary,joining_date='$joindate' where user_id=$uid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
			return 0;
	}
	function updateEmployeeDepartment($uid,$dept){
		if($this -> link)
		{
			$query="update users set department='$dept' where id=$uid";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
			return 0;
	}

function getUserInfo($user_id)
{
		if($this -> link)
		{
		//	  $query = "select joining_date,salary from users_info where user_id = $user_id ";
			$query="select   users_info.joining_date,users_info.salary,users.department from users LEFT JOIN users_info ON users.id=users_info.user_id where users.id=$user_id ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
}
function getUserDetails($uid)
{
	if ($this->link)
	{
		$query="SELECT image_src,first_name,last_name,title,department FROM users WHERE id=$uid";
		$result=mysql_query($query,$this->link);
		if (mysql_affected_rows()>0)
		{
			return $result;
		}
		return 0;
	}
	return 0;
}

function updateUserInfo($user_id,$data)
{
		if($this -> link)
		{
		  $value = $data["value"];
		  if($data["col"] == 'joining_date')
			  $query = "update users_info set joining_date = $value where users_info.user_id = $user_id ";
		  else
			  $query = "update users_info set salary = $value where users_info.user_id = $user_id ";
		  $result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result;
			}
			return 0;
		}
		return 0;
    	
}
function getUserFBId($user_id)
{
	$query = "select fbid,email_id,image_src from users where id = $user_id ";
	$result = $this -> runQuery($query);
	$row = mysql_fetch_array($result);
	$arr['email_id'] = $row['email_id'];
	$arr['fbid'] = $row['fbid'];
	$arr['image_src'] = $row['image_src'];
	return $arr;
}

function updateFBUser($user_fbid , $user_id, $email ,$image_src , $gender) 
{
		if($this -> link)
		{
		
			$user_info = $this -> getUserFBId($user_id);
			if((($user_info['fbid'] != $user_fbid) && $user_info['fbid'] != ''))
			{
				$warning = -1;
				return $warning;
			}
			if($user_info['fbid'] == null)
			{
				$query = "update users set fbid = '$user_fbid' where id = $user_id ";
				mysql_query($query);
			}
			if($user_info['email_id'] == null)
			{
				$query = "update users set email_id = '$email' , activated = 1 where id = $user_id ";
				mysql_query($query);
			}
		
			if($user_info['image_src'] == null)
			{
				$query = "update users set image_src = '$image_src' where id = $user_id ";
				mysql_query($query);
			}
				
			if(($user_info['email_id'] != $email))
			{
				$query = "update users set fb_email  = '$email' where id = $user_id ";
				mysql_query($query);
			}
				$query = "update users set gender = '$gender' where id = $user_id ";
				mysql_query($query);
//			$query = "update users set fbid = '$user_fbid' ,  email_id = '$email' , image_src = '$image_src' , activated = 1 ,user_type = 4 where id = $user_id";
			
//			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
}
function ifUserExists($user_fbid , $email ,  $image_src , $gender)
{
		if($this -> link)
		{
		 $query = "select id from users where fbid = '$user_fbid'" ;
			$result=mysql_query($query,$this->link);
			/*echo "query = $query";
			echo "fbid = $user_fbid ";
			echo "result= $result ";*/

			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_array($result);
				$user_id = $row["id"];
				//echo "id = $user_id ";
				
				$this -> updateFBUser($user_fbid , $user_id, $email ,$image_src , $gender);
				return $user_id;
			}
			else
			{
			if($email != null)
			{
			$query = "select id from users where email_id = '$email' ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_array($result);
				$user_id = $row["id"];
				$this -> updateFBUser($user_fbid , $user_id, $email ,$image_src , $gender);
				return $user_id; 
			}
			}
			}
			return 0;
		}
		return 0;
}
function insertFBUser($user_fbid ,$email,$name,$image_src,$gender, $cid)
{
	$Name=explode(" ",$name, 2);
	$fName=$Name[0];
	$lName=$Name[1];
	//echo $email;
		if($this -> link)
		{
	
			$id = $this -> ifUserExists($user_fbid,$email,$image_src,$gender);
			if($id)
		{
				return $id;
		}
			else
			{
			if($email)
			{
				$query = "insert into users(fbid,first_name,last_name,image_src,email_id,activated,user_type, gender, company_id) values('$user_fbid','$fName','$lName','$image_src','$email',1,4, '$gender', $cid) ";
			}
			else
			{
				$query = "insert into users(fbid,first_name,last_name,image_src,user_type, gender, company_id) values('$user_fbid','$fName','$lName','$image_src',4, '$gender', 0) ";
			}
			
//			echo $query;
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				$q = "Select LAST_INSERT_ID() FROM users ";
				$r = mysql_query($q);
				$ro = mysql_fetch_row($r);
				return $ro[0];
			}
			return 0;
			}
		}
		return 0;
	
}
public function runQuery($query)
{
		if($this->link)
		{
			$result=mysql_query($query,$this->link);

			if(mysql_affected_rows()>0)
			{
				return $result; 
			}
			return 0;
		}
		return 0;
}

function getDirectPoints($user_id)
{
$query = "SELECT sum(user1_points) as points  from user_points where user1_id = $user_id ";
	return $this -> runQuery($query);

}
function getIndirectPoints($user_id)
{
$query = "SELECT sum(user2_points) as points  from user_points where user2_id = $user_id ";
	return $this -> runQuery($query);

}


function WinPoints($userid)
	{
		if($this->link)
		{
			$query = "SELECT sum(points) as points  from winners where userid = $userid ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
		}
	return 0;
	}

	function BidPoints($userid)
	{
		if($this->link)
		{
			$query = "SELECT sum(points) as points  from bids where userid = $userid ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;
		}
	return 0;
	}

	function IndirectPoints($userid)
	{
		$query = "SELECT sum(user2_points) as points  from user_points where user2_id = $userid ";
		$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				return $row[0];
			}
			return 0;


	}

	function RemPoints($userid)
	{
		if($this->link)
		{
			$query = "SELECT sum(user1_points) as points  from user_points where user1_id = $userid ";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_row($result);
				$pt1 = $row[0];
				$pt2 = $this->IndirectPoints($userid);
				$pt3 = $this->BidPoints($userid);
				$pt4 = $this->WinPoints($userid);
				return $pt1+$pt2-($pt3+$pt4);
				
			}
			return 0;
		}
		return 0;
	}






function getUserIdByFBId($fbid)
{
	$query = "select id from users where fbid = '$fbid' ";
	return $this -> runQuery($query);

}

public function getAllEmployees($company_id)
{
		if($this->link)
		{
			$query = "SELECT * from users where company_id = $company_id";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return $result;
			}
		}
		return 0;
	
}
}


?>

