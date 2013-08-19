<?php
class registerDB extends DB
{
	

        public function checkLogin($email, $password)
        {
                if($this->link)
                {
                        $query = "select id from users where email_id='$email' and password='$password' and activated=1";
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

	 public function addBadgeDate($company_id)
        {
                if($this->link)
                {
                        $query = " insert into company_badges(company_id,start_date,end_date)values($company_id,NOW(),DATE_ADD(NOW(),INTERVAL 90 DAY))";                    $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                  //      $row = mysql_fetch_row($result);
                        return 1;
                        }

                        return 0;
                }
                return 0;
        }
	 public function addSubscriber($email_id)
        {
                if($this->link)
                {
                        $query = " insert into subscribers(email_id,add_time)values('$email_id',NOW())"; 
	                   $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                  //      $row = mysql_fetch_row($result);
                        return 1;
                        }

                        return 0;
                }
                return 0;
        }
	

        public function checkExistingUser($email)
        {
                if($this->link)
                {
                        $query = "select id from users where email_id='$email'";
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

	public function IfUserActivated($email)
        {
                if($this->link)
                {
                        $query = "select activated from users where email_id='$email'";
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

		
        public function checkExistingDomain($domain)
        {
                if($this->link)
                {
                        $query = "select id from companies where domain='$domain'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }

                        return 0;
                }
                return 0;
        }

	public function checkDomain($domain)
        {
/*	$complete_dom = split($domain, ".");
	$domain = $complete_dom[0]; */
                if($this->link)
                {
                        $query = "select id from domains where name = '$domain'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return true;
                        }

                        return false;
                }
                return false;
        }


        public function getCompanyId($domain)
        {
                if($this->link)
		{
                        $query = "select id from companies where domain='$domain'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
                                return $row['id'];

                        }

                        return 0;
                }
                return 0;
        }

	public function isOpen($domain)
        {
                if($this->link)
		{
                        $query = "select open from companies where domain='$domain'";
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

	public function getUserCompany($uid)
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


	public function addCompany($company)
	{
		if($this->link)
		{
			$query="insert into companies (name,domain, add_time) values ('$company','$company', NOW())";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
	 public function addBadge($badgeId,$companyId,$points)
        {
                if($this->link)
                {
                        $query="insert into company_badge_points (company_id,badge_id,total_points) values ($companyId,$badgeId,$points)";
                        $result=mysql_query($query,$this->link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }
                        return 0;
                }
                return 0;
        }

	public function insertUser($email,$fname,$lname,$gender,$company,$title,$userType,$phone, $password, $activated)
	{
		if($this->link)
		{
			$query="insert into users (email_id,first_name,last_name,gender,company_id,title,user_type,phone, password, activated, add_time,badge_points,remaining_badge_points) values ('$email','$fname','$lname','$gender','$company','$title',$userType,'$phone', '$password', $activated, NOW(),100,100)";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

	public function insertUserAuth($id, $email,$password, $activated)
	{
		if($this->link)
		{
			$query="insert into user_auth (id, email_id, password, activated) values ($id, '$email','$password',$activated)";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}


        public function getUserId($email)
        {
                if($this->link)
		{
                        $query = "select id from users where email_id='$email'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
                                return $row['id'];

                        }

                        return 0;
                }
                return 0;
        }


	public function makeDomainAdmin($uid, $company_id, $level)
	{
		if($this->link)
		{
                        $query = "insert into domain_admin values ($uid, $company_id, $level)";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				return 1;
                        }

                        return 0;
                }
                return 0;

	}


	public function insertActivationKey($id,$key)
	{
		if($this->link)
		{
			$query="delete from user_activation where user_id = '$id'";
		mysql_query($query);
			$query="insert into user_activation (user_id,activation_key) values ('$id','$key')";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

        public function searchKey($key)
        {
                if($this->link)
		{
                        $query = "select user_id from user_activation where activation_key='$key'";
	$result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
                                return $row['user_id'];

                        }

                        return 0;
                }
                return 0;
        }
        public function getTime($uid)
        {
                if($this->link)
		{
                        $query = "select add_time from users where id='$uid'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
                                return $row['add_time'];

                        }

                        return 0;
                }
                return 0;
        }

	public function getInviteKey($ikey)
        {
                if($this->link)
		{
                        $query = "select invited, company_id, companies.name from invitations, companies where invitations.company_id = companies.id and ikey='$ikey' and invitations.valid=1";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_row($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }
	public function getInvitationType($ikey)
        {
                if($this->link)
		{
                        $query = "select invitation_type from invitations where  ikey='$ikey' and invitations.valid=1";
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
	
	public function checkInvited($email)
        {
                if($this->link)
		{
                        $query = "select company_id from invitations where ikey='$ikey' and invitations.valid=1";
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

	public function getTotalInvitedEmployee($cid)
        {
                if($this->link)
		{
                        $query = "select Count(*) from invitations where company_id=$cid";
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

	
	
	public function removeInviteKey($ikey)
        {
                if($this->link)
		{
                        $query = "update invitations set valid=0 where ikey='$ikey'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_row($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }
	 public function updateCompanyId($uid,$companyId)
        {
                if($this->link)
                {
                        $query = "update users set company_id=$companyId where id=$uid";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_row($result);
                                return $row;
                        }

                        return 0;
                }
                return 0;
        }



	public function activateUser($id)
	{
		if($this->link)
		{
			$query="update users set activated=1 where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
	public function removeKey($key)
	{
		if($this->link)
		{
			$query="delete from user_activation where activation_key='$key'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

        public function emailExists($email)
        {
                if($this->link)
                {
                        $query = "select email_id from users where email_id='$email'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return 1;
                        }

                        return 0;
                }
                return 0;
        }



        public function getUserInfo($email)
        {
                if($this->link)
                {
                        $query = "select id,first_name,password,activated from users where email_id='$email'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }
	public function updateImage($image,$id)
	{
		if($this->link)
		{
			$query="update users set image_src='$image' where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}

        public function getUserInfoId($id)
        {
                if($this->link)
                {
                        $query = "select email_id, first_name, last_name, gender, title, phone, MBTIScore, image_src from users where id='$id'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }


	public function getUserBasicInfo($id)
        {
                if($this->link)
                {
                        $query = "select email_id, first_name from users where id='$id'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_row($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }


	public function updateUser($id,$fname,$lname,$gender, $title,$phone, $mbti)
	{
		if($this->link)
		{
			$query="update users set first_name='$fname', last_name='$lname', gender='$gender', title='$title', phone='$phone', MBTIScore='$mbti' where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
	public function updateBio($id,$bio)
	{
		if($this->link)
		{
			$query="update market_users set bio=\"$bio\" where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
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
	public function addBio($id)
	{
		if($this->link)
		{
			$query="insert into market_users (id) values ($id)";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}







	public function updateCompany($cid,$cname,$eLimit,$address, $phone,$open)
	{

		if($this->link)
		{
			$query="update companies set name='$cname', employee_limit='$eLimit', address='$address', phone='$phone', open='$open' where id='$cid'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;





	}

	public function checkPass($id,$pass)
	{
		if($this->link)
		{
			$query="select email_id from users where id='$id' and  password='$pass'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return true;
			}
			return 0;
		}
		return 0;
	}

	public function updatePass($id,$pass)
	{
		if($this->link)
		{
			$query="update users set password='$pass' where id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return true;
			}
			return true;
		}
		return 0;
	}

	public function getCompanyInfo($id)
        {
                if($this->link)
                {
                        $query = "select name,domain,open,employee_limit,address,phone,image_src from companies where id='$id'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }
	public function getInvitationLimit($cid)
        {
                if($this->link)
                {
                        $query = "select employee_limit from companies where id='$cid'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
				return $row[0];
                        }

                        return 0;
                }
                return 0;
        }
	

	public function insertRecoveryKey($uid,$key)
	{
		if($this->link)
		{
			$query="delete from password_recovery where user_id=$uid";
mysql_query($query);
			$query="insert into password_recovery values ($uid,'$key',NOW())";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
        public function getPasswordInfo($key)
        {
                if($this->link)
                {
                        $query = "select * from password_recovery where recovery_key='$key'";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                $row=mysql_fetch_array($result);
				return $row;
                        }

                        return 0;
                }
                return 0;
        }
	public function deleteRecoveryKey($id)
	{
		if($this->link)
		{
			$query="delete from password_recovery where user_id='$id'";
			$result=mysql_query($query,$this->link);
			if(mysql_affected_rows()>0)
			{
				return 1;
			}
			return 0;
		}
		return 0;
	}
        public function searchRecoveryKey($key)
        {
                if($this->link)
		{
                        $query = "select user_id, email_id, HOUR(TIMEDIFF(NOW(), timestamp)) from password_recovery, users  where password_recovery.user_id = users.id and recovery_key='$key'";
			
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				$row = mysql_fetch_row($result);
                                return $row;

                        }

                        return 0;
                }
                return 0;
        }
	 public function getAllUserTypes()
        {
                if($this->link)
                {
                        $query = "select id,type from user_types ";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }

                        return 0;
                }
                return 0;
        }
	 public function getUserType($id)
        {
                if($this->link)
                {
                        $query = "select user_type from users where id=$id ";
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


}

?>