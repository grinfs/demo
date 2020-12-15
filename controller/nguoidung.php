<?php 
/**
 * lớp truy vấn cơ sở dữ liệu của bảng người dùng 
 */
class nguoidung extends connectDB
{
	// lấy tất cả thông tin người dùng hiện có
	public function getAll()
	{
		$stmt= $this->getConnect()->query("SELECT `ma`, `email`, `sdt`, `ten`, 'ho' 
			FROM `nguoidung`");
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Hàm lấy thông tin của 1 người dùng
	 * đầu vào: mail hoặc sđt ; mật khẩu
	 * đầu ra: thông tin người dùng (nếu có) >< NULL
	 */
	public function logIn($user='', $pass='')
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE (email = ? OR sdt = ?)
			AND matkhau = ?");
		$stmt->execute(array($user, $user, hash('whirlpool',$pass)));
		// $stmt->debugDumpParams();
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			return $row;
		return NULL;
	}

	/**
	 * lấy thông tin người dùng từ email hoặc số điện thoại
	 * đầu ra: thông tin người dùng (nếu có) >< NULL
	 */
	public function getFromKey($key='')
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE email like ? OR sdt like ?");
		$stmt->execute(array($key, $key));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			return $row;
		return NULL;
	}
	
	/**
	 * lấy thông tin người dùng từ mã
	 * đầu ra: thông tin người dùng (nếu có) >< NULL
	 */
	public function getFromId($id)
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE ma = ?");
		$stmt->execute(array($id));
		// $stmt->debugDumpParams();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			return $row;
		return NULL;
	}

	public function hasAccount($mail='', $pass='')
	{
		if ($this->logIn($mail,$pass) == NULL)
			return FALSE; 
		return TRUE;
	}
	
	public function changePass($mail='',$passOld='',$passNew='')
	{
		if (!$this->hasAccount($mail,$passOld)) {
			return FALSE;
		} else {
			$stmt = $this->getConnect()->prepare("UPDATE `nguoidung` SET `matkhau`= ? WHERE `email` LIKE ?");
			$stmt->execute(array(hash('whirlpool',$passNew), $mail));
			
			return $stmt->rowCount();	
		}
	}
	
	public function sameMail($mail='')
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE email like ?");
		$stmt->execute(array($mail));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			return TRUE;
		return FALSE;
	}	
	
	public function samePhone($phone='')
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE sdt like ?");
		$stmt->execute(array($phone));
		$stmt->execute();
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			return TRUE;
		return FALSE;
	}
	
	/**
	 * kiểm tra đã có só điện thoại hoặc email
	 */
	public function same($input)
	{
		return $this->sameMail($input) || $this->samePhone($input);
	}
	
	public function changeInfo($id, $firstName, $lastName,$phone,$birthyear)
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ten`, 'ho'
			FROM `nguoidung` 
			WHERE ma like ?");
		$stmt->execute(array($id));
		// $stmt->debugDumpParams();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			
			$stmt = $this->getConnect()->prepare("UPDATE `nguoidung` SET `sdt`= ?, `ten`= ?, 'ho' = ?, 'ten' = ? WHERE `ma` LIKE ?");
			$stmt->execute(array($phone, $firstName, $lastName, $id));
			// $stmt->debugDumpParams();
			return $stmt->rowCount()>=0;
		}
		
		return false;
	}
	
	public function changeInfoHasAvatar($id, $firstname, $lastname, $phone, $avatar, $birthyear)
	{
		$stmt = $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `ho`, `ten`
			FROM `nguoidung` 
			WHERE ma like ?");
		$stmt->execute(array($id));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			
			$stmt = $this->getConnect()->prepare("UPDATE `nguoidung` SET `sdt`= ?, `ho` = ?, `ten`= ? WHERE `ma` LIKE ?");
			$stmt->execute(array($phone, $firstname, $lastname, $id));
			
			return $stmt->rowCount()>=0;
		}
		
		return false;
	}
	
	public function addUser($mail, $phone, $pass, $firstName, $lastName)
	{
		if (strlen($mail)==0 || strlen($phone)==0 || strlen($pass)==0 || strlen($firstName)==0 || strlen($lastName)==0) {
			return -3;
		} elseif ($this->sameMail($mail)) {
			return -2;
		} elseif ($this->samePhone($phone)) {
			return -1;
		} else {
			$stmt = $this->getConnect()->prepare("INSERT INTO `nguoidung`(`email`, `sdt`, `ho`, `ten`, `matkhau`) VALUES (?,?,?,?,?)");
			$stmt->execute(array($mail, $phone, $lastName, $firstName, hash('whirlpool',$pass)));

			return $this->getConnect()->lastInsertId();
		}
	}

	public function search($key='')
	{
		$stmt= $this->getConnect()->prepare("SELECT `ma`, `email`, `sdt`, `hoten`, `avatar`
			FROM `nguoidung` 
			WHERE `email` LIKE ? OR `sdt` LIKE ? OR `hoten` LIKE ?;");
		$stmt->execute(array($key,$key,'%'.$key.'%'));
		//$stmt->debugDumpParams();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>