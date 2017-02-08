<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Job.php";

    session_start();
    define('JOB_SESSION_KEY', 'list_of_jobs');
    if (empty($_SESSION[JOB_SESSION_KEY])) {
        $_SESSION[JOB_SESSION_KEY] = array();
    }

    $app = new Silex\Application();

    $app->get('/', function() {
        $jobs = Job::getAll();

        $output = '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
                <title>Job History</title>
            </head>
            <body>
                <div class="container">';

        if ($jobs) {
            $output .= "<h2>Jobs Entered So Far</h2>";
        }

        foreach ($jobs as $job) {
            $output .= "<h3>" . $job->getWhenEmployed();
            $output .= " / " . $job->getJobTitle();
            $output .= " / " . $job->getEmployer() . "</h3>";
        }

        if ($jobs) {
            $output .= "<br>";
        }

        $output .=
                '<h2>Please enter a job to add to your employment history:</h2>
                    <form action="/added_job" method="post">
                        <div class="form-group">
                            <label for="when_employed">When were you employed?</label>
                            <input id="when_employed" class="form-control" name="when_employed" type="text">
                        </div>
                        <div class="form-group">
                            <label for="job_title">Title</label>
                            <input id="job_title" class="form-control" name="job_title" type="text">
                        </div>
                        <div class="form-group">
                            <label for="employer">Who was your employer?</label>
                            <input id="employer" class="form-control" name="employer" type="text">
                        </div>
                        <button class="btn" type="submit">Add a Job!</button>
                    </form>
                    <br>
                    <form action="/delete_all" method="post">
                        <button class="btn btn-warning" type="submit">Delete All Jobs</button>
                    </form>
                </div>
            </body>
        </html>

        ';

        return $output;
    });


    $app->post('/added_job', function() {
        $job = new Job(
            $_POST['when_employed'],
            $_POST['job_title'],
            $_POST['employer']
        );
        $job->save();

        $output = '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
                <title>Job History</title>
            </head>
            <body>
                <div class="container">
                    <h1>You added a job!</h1>';

                    $output .= "<h3> Employment Period: " . $job->getWhenEmployed();
                    $output .= "<br> Job Title: " . $job->getJobTitle();
                    $output .= "<br> Employer: " . $job->getEmployer() . "</h3>" .

                    '<p><a href="/">See all jobs and add another!</a></p>
                </div>
            </body>
        </html>
        ';

        return $output;

    });

    $app->post('/delete_all', function() {
        Job::deleteAll();

        $output = '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
                <title>Job History</title>
            </head>
            <body>
                <div class="container">
                    <h1>Job list has been cleared.</h1>
                    <p><a href="/">Add a new job!</a></p>
                </div>
            </body>
        </html>
        ';

        return $output;
    });



    return $app;

?>
