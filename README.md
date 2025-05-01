# AccelleronTest
REST API for managing tech events and attendee registrations






# Step 1
git clone https://github.com/deepanshu016/AccelleronTest.git
cd AccelleronTest
composer install

# Step 2
cp .env.example .env
php artisan key:generate


# Step 3
php artisan migrate


# Manage All expired Event

php artisan app:manage-expired-events


# Manage Notification for waiting list 
php artisan queue:work


# Postman Collection for each APIs has been pushed on github






