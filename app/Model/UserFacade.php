<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator
{
	public const PasswordMinLength = 7;

	private const
		TableName = 'users',
		ColumnId = 'id',
		ColumnName = 'username',
		ColumnPasswordHash = 'password',
		ColumnEmail = 'email',
		ColumnRole = 'role',
		ColumnImage = 'image';


	public function __construct(
		private Nette\Database\Explorer $database,
		private Passwords $passwords,
	) {
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
	{
		$row = $this->database->table(self::TableName)
			->where(self::ColumnName, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
			$row->update([
				self::ColumnPasswordHash => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::ColumnPasswordHash]);
		return new Nette\Security\SimpleIdentity($row[self::ColumnId], $row[self::ColumnRole], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{
		Nette\Utils\Validators::assert($email, 'email');
		try {
			$this->database->table(self::TableName)->insert([
				self::ColumnName => $username,
				self::ColumnPasswordHash => $this->passwords->hash($password),
				self::ColumnEmail => $email,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

	public function getAll(int $id = null)
	{
		// Use $id to fetch a specific user if provided, otherwise fetch all users
		if ($id !== null) {
			return $this->database->table('users')->where('id', $id)->fetch();
		} else {
			return $this->database->table('users')->fetchAll();
		}
	}
	public function getById(int $userId) {
		return $this->database->table(self::TableName)->get($userId);
	}
	public function getByUserName(string $username) {
		$user = $this->database
			->table(self::TableName)
			->where(self::ColumnName, $username)
			->fetch();
		return $user;
	}
	public function edit(int $userId, $data): void 
	{	
		$this->getById($userId)->update($data);
	}
	
	public function changePassword(int $userId, string $newPassword) {
		$user = $this->getById($userId);
		$user->update([
			self::ColumnPasswordHash => $this->passwords->hash($newPassword),
			// self:ColumnPasswordHash
		]);
	}

	public function delete(int $userId) {
		$this->getById($userId)->delete();
	}

}



class DuplicateNameException extends \Exception
{
}
