# WP Email Async

Make wp_mail to be sent to wp_cron to ensure faster user experience when WordPress is sending an email. It's an asynchronous-like experience as it will make wp_mail fast by queueing it to the next 2 seconds.

## Why WP Cron?

Since an HTTP request to wp cron will not slow down the visitor who happens to visit when the cron job is needed to run, it's best to move the load from the user view to wp cron.

Inside wp-cron.php file, there is:

```
ignore_user_abort( true );
```

And, the process will be spawned by the server to server to call the wp-cron.php. Hence, wp cron is simply an excellent candidate to do this expensive task.

## Reference

[WordPress Stack Exchange](https://wordpress.stackexchange.com/questions/185295/how-to-make-wordpress-emails-async)