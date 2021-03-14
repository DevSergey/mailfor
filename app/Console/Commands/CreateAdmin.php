<?php
namespace App\Console\Commands;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a new admin user.';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $name = $this->ask('What is your username?');
        $email = $this->ask('What is your email address?');
        $password = $this->ask('What is your password?');
        $password_confirmation = $this->ask('Repeat your password');
        $validator = $this->validator([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ]);
        if ($validator->fails()) {
            $errors = new Collection([]);
            foreach ($validator->errors()->toArray() as $key => $error) {
                $errors->push($key);
            }
            $errorMessage = 'Invalid input parameters: ' . $errors->join(', ');
            $this->error($errorMessage);
            return 1;
        }
        $validator->validate();
        $this->create($validator->validated());
        $this->info('Successfully created admin account');
        return 0;
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->admin = true;
        $user->save();
        return $user;
    }
}
