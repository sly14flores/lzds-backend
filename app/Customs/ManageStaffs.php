<?php

namespace App\Customs;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

trait ManageStaffs
{
	public function start() {

		$choice = '0';
		$choices = ['0','1','2','3'];

		while (in_array($choice,$choices)) {


			$this->info("\nOptions: 1 = List, 2 = Create, 3 = Delete, Any Character = Exit");
			$choice = $this->ask("Enter option [1-3]: ");

			switch ($choice) {
				case '1':
					$this->displayUsers();
				break;
				case '2':
                    $details = $this->getDetails();
					$user = $this->createUser($details);
					if (gettype($user)=="object") {
						$this->displayUser($user);
					}
				break;
				case '3':
					$this->deleteUser();
				break;
			}

		}

	}

    public function createUser(array $details)
    {
		try {

			$user = new User;

			$user->staff_id = $details['staff_id'];
			$user->email = $details['email'];
			// $user->is_super_admin = $details['is_super_admin'];
			$user->password = $details['password'];
			// $user->email_verified_at = now();
			$user->created_at = now();
			$user->updated_at = now();

			$user->save();

			return $user;

		} catch(\Exception $e) {
			if ($e instanceof QueryException) {
				if ($e->getCode()==="23000") {
					echo "User with email: ".(($e->getBindings())[0])." already exists\n";
					return false;
				}
			}
		}
	}

	public function deleteUser()
	{
		$this->displayUsers();
		
        $id = $this->ask('Enter user id to delete [0 to cancel]: ');

		if ($id == '0') {
			return 0;
		}

        $user = User::where('id',$id);
        $user->forceDelete();
		
		$this->displayUsers();	
	}
	
    /**
     * Ask for admin details.
     *
     * @return array
     */
    private function getDetails() : array
    {

        $id = $this->ask('Staff id (primary)');

        $details['password'] = $this->secret('Password');
        $details['confirm_password'] = $this->secret('Confirm password');

        while (! $this->isValidPassword($details['password'], $details['confirm_password'])) {
            if (! $this->isRequiredLength($details['password'])) {
                $this->error('Password must be more that six characters');
            }

            if (! $this->isMatch($details['password'], $details['confirm_password'])) {
                $this->error('Password and Confirm password do not match');
            }

            $details['password'] = $this->secret('Password');
            $details['confirm_password'] = $this->secret('Confirm password');
        }

		$details['password'] = Hash::make($details['password']);

        $staff = Staff::find($id);

        if (is_null($staff)) {
            $this->info("No staff found for id {$id}");
            $this->start();
        }

        $details['staff_id'] = $id;
        $details['email'] = $staff['email'];

        return $details;
	}

    private function displayUser(User $user) : void
    {
        $headers = ['Id','StaffID','Email'];

        $fields = [
			'Id' => $user->id,
            'StaffID' => $user->staff_id,
            'Email' => $user->email,
        ];

        $this->info('Staff Login created');
        $this->table($headers, [$fields]);
	}
	
    private function displayUsers() : void
    {
        $headers = ['Id','StaffID','Email'];

		$users = User::whereNotNull('staff_id')->get();

		$rows = [];
		foreach ($users as $user) {
			$rows[] = [
				'Id' => $user->id,
                'StaffID' => $user->staff_id,
                'Email' => $user->email,            
			];
		}

        $this->info('Staffs Logins');
        $this->table($headers, $rows);
    }	

    /**
     * Check if password is vailid
     *
     * @param string $password
     * @param string $confirmPassword
     * @return boolean
     */
    private function isValidPassword(string $password, string $confirmPassword) : bool
    {
		return $this->isRequiredLength($password) &&
        		$this->isMatch($password, $confirmPassword);
    }

    /**
     * Check if password and confirm password matches.
     *
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    private function isMatch(string $password, string $confirmPassword) : bool
    {
        return $password === $confirmPassword;
    }

    /**
     * Checks if password is longer than six characters.
     *
     * @param string $password
     * @return bool
     */
    private function isRequiredLength(string $password) : bool
    {
        return strlen($password) > 7;
    }	

}

?>