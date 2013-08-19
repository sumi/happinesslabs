<?php
class badgeDB extends DB
{

	public function getBadgeIdByThankId($thank_id)
	{
                if($this->link)
                {
			$query = "SELECT badges.badgeid,badgename,url
				FROM thanks,badges
				WHERE id = $thank_id
				and thanks.badgeid = badges.badgeid ";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				return $result;
                        }

                        return 0;
                }
                return 0;
	}
	public function getBadge($badgeid,$compId)
	{
                if($this->link)
                {
			$query = "SELECT badgename,url,total_points FROM badges,company_badge_points WHERE badgeid = $badgeid and badges.badgeid=company_badge_points.badge_id and company_id=$compId";
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


        public function getBadges()
        {
                if($this->link)
                {
$query="select badgeid, badgename, url,badge_points from badges";
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
				return $result;
                        }

                        return 0;
                }
                return 0;
        }
	 public function getBadgesByCompanyId($compId,$selected)
        {
                if($this->link)
                {
		$query="select distinct badgeid, badgename,url,total_points,badge_title,selected from badges,company_badge_points where badges.badgeid=company_badge_points.badge_id and company_id=$compId ";
	if($selected == null)
		$query .= " and selected = 1 ";

	$query .= " order by selected desc, badgeid desc";
		
                        $result = mysql_query($query , $this -> link);
                        if(mysql_affected_rows()>0)
                        {
                                return $result;
                        }

                        return 0;
                }
                return 0;
        }

	public function getrandomBadge($rempts, $cid)
        {
                if($this->link)
                {
$query="select distinct badgeid, badgename, url, total_points,badge_title from badges,company_badge_points where badges.badgeid = company_badge_points.badge_id and company_id = $cid and  company_badge_points.total_points <= $rempts and selected = 1 order by RAND() limit 1";
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



	public function getBadgeUrl($badgeid)
        {
                if($this->link)
                {
$query="select  url from badges where badgeid=$badgeid";
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
	public function saveBadgePoints($badgeId,$points,$compId,$badge_title,$selected)
	{
        if($this->link)
        {
                $query="update company_badge_points set total_points=$points , badge_title = '$badge_title' , selected = $selected where badge_id=$badgeId and company_id=$compId ";
                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
                        return 1;
                }
                return 0;
        }
        return 0;
	}
	public function countSelectedBadges($company_id)
	{
        if($this->link)
        {
                $query="select count(distinct badge_id) as count_badges from company_badge_points where company_id=$company_id and selected = 1";
                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
                        $row = mysql_fetch_array($result);
			return $row['count_badges'];
                }
                return 0;
        }
        return 0;
	}
	public function ifSelectedBadge($company_id,$badge_id)
	{
        if($this->link)
        {
                $query="select id from company_badge_points where company_id=$company_id and selected = 1 and badge_id= $badge_id ";
                $result=mysql_query($query,$this->link);
                if(mysql_affected_rows()>0)
                {
			return 1;
                }
		else
	                return 0;
        }
        return 0;
	}




}
?>

