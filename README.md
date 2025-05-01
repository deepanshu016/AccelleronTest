# AccelleronTest
REST API for managing tech events and attendee registrations






# Step 1
<pre>
    <code id="git-url">
        git clone https://github.com/deepanshu016/AccelleronTest.git
    </code>
</pre>
<pre>
    <code id="git-copy">
        cd AccelleronTest
    </code>
</pre>
<pre>
    <code id="composert-install">
        composer install
    </code>
</pre>

# Step 2
<pre>
    <code id="env-data">
        cp .env.example .env
    </code>
</pre>

<pre>
    <code id="key-generate">
        php artisan key:generate
    </code>
</pre>



# Step 3
<pre>
    <code id="artisan-migrate">
        php artisan migrate
    </code>
</pre>


# Manage All expired Event
<pre>
    <code id="manage-expired-events">
        php artisan app:manage-expired-events
    </code>
</pre>


# Manage Notification for waiting list 
<pre>
    <code id="queue-work">
       php artisan queue:work
    </code>
</pre>



**Postman Collection for each APIs has been pushed on github** 






