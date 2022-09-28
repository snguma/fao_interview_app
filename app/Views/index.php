<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Test</title>
</head>
<body>
<h6 class="mt-3"> List of Synced Users</h6>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Gender</th>
        <th>Age</th>
    </tr>
    </thead>
    <tbody>
  <?php foreach ($data as $user){?>
      <tr>
      <td><?php echo $user['firstname'] ?></td>
      <td><?php echo $user['lastname'] ?></td>
      <td><?php echo $user['gender'] ?></td>
      <td><?php echo $user['age'] ?></td>
      </tr>
 <?php  } ?>
    </tbody>
</table>

<h6>Clone Api</h6>
<p>Option 1</p>
<p>{* * * * * "usr/bin curl -v H User/Agent Mozilla 5.0, localhost/interview_test/api/users/sync_data}</p>

<p>Option 2</p>
<p>If server has php installed, From terminal type crontab -e , it will open cron tab,
    then add the url to the script , for our case /xamp/htdocs/interview_test/api/users</p>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>