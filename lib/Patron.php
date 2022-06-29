<?
use Safe\DateTime;

class Patron extends PropertiesBase{
	protected $User = null;
	public $UserId = null;
	public $IsAnonymous;
	public $AlternateName;
	public $IsSubscribedToEmail;
	public $Timestamp = null;
	public $DeactivatedTimestamp = null;

	public static function Get(?int $userId): Patron{
		$result = Db::Query('SELECT * from Patrons where UserId = ?', [$userId], 'Patron');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPatronException();
		}

		return $result[0];
	}

	public static function GetByEmail(?string $email): Patron{
		$result = Db::Query('SELECT p.* from Patrons p inner join Users u on p.UserId = u.UserId where u.Email = ?', [$email], 'Patron');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPatronException();
		}

		return $result[0];
	}

	public function Create(bool $sendEmail = true): void{
		$this->Timestamp = new DateTime();

		Db::Query('INSERT into Patrons (Timestamp, UserId, IsAnonymous, AlternateName, IsSubscribedToEmail) values(?, ?, ?, ?, ?);', [$this->Timestamp, $this->UserId, $this->IsAnonymous, $this->AlternateName, $this->IsSubscribedToEmail]);

		if($sendEmail){
			$this->SendWelcomeEmail();
		}
	}

	public function Reactivate(bool $sendEmail = true): void{
		Db::Query('UPDATE Patrons set Timestamp = utc_timestamp(), DeactivatedTimestamp = null, IsAnonymous = ?, IsSubscribedToEmail = ?, AlternateName = ? where UserId = ?;', [$this->IsAnonymous, $this->IsSubscribedToEmail, $this->AlternateName, $this->UserId]);
		$this->Timestamp = new DateTime();
		$this->DeactivatedTimestamp = null;

		if($sendEmail){
			$this->SendWelcomeEmail();
		}
	}

	private function SendWelcomeEmail(): void{
		$this->__get('User');
		if($this->User !== null){
			$em = new Email();
			$em->To = $this->User->Email;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->Subject = 'Thank you for supporting Standard Ebooks!';
			$em->Body = Template::EmailPatronsCircleWelcome(['isAnonymous' => $this->IsAnonymous]);
			$em->TextBody = Template::EmailPatronsCircleWelcomeText(['isAnonymous' => $this->IsAnonymous]);
			$em->Send();
		}
	}
}
