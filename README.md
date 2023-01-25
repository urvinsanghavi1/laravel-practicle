<img src="/public/img/Company-Registration.png" alt="laravel-practice" />

## Steps To Setup Project

- Copy .env.example file and change name to .env
- Add Database name with username and password
- Run Migration command to create table (php artisan migrate)
- Run Seeder Command to create super user (php artisan db:seed)
- Go to http://laravel-test.local/ (hostname)

## Project Flow

Super Admin
- Super admin login details alredy created by sedder.
- admin can see the panal after login.

Company Admin
- Guest User can register new company.
- After registration user get the url and login creadancial in their email.
- After click on URL company user redirect to login page
- After Login user can see the Company Panal
- User can update their profile after login into company panal.

## Login Flow

- Got to {hostname}.
- Add valid login details
- Redirect user to dashboard

## Register Flow

- Go to {hostname}/register URL.
- Add details into registration form.
- Run queue command for background process (php artisan queue:work)


Note: Log File - Laravel.log