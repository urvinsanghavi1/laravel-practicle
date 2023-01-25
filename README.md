<img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Findiabizassist.com%2Fcompany-registrations%2F&psig=AOvVaw2I_45fXHNgNHo436olKUZ1&ust=1674719585580000&source=images&cd=vfe&ved=0CBAQjRxqFwoTCKi0--ie4vwCFQAAAAAdAAAAABAI" alt="laravel-practice" />

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