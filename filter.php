<?php

// __________ TOUTDATED FILTER, DO NOT USE____________


if (isset($_GET['filter'])) {
    $sport = isset($_GET['sport']) ? urlencode(trim($_GET['sport'])) : '';
    $date = isset($_GET['date']) ? urlencode(trim($_GET['date'])) : '';

    $queryFilter = [];
    if (!empty($sport)) {
        $queryFilter['sport'] = $sport;
    }
    if (!empty($date)) {
        $queryFilter['date'] = $date;
    }

    $url = 'index.php';
    if (!empty($queryFilter)) {
        $url .= '?' . http_build_query($queryFilter);
    }

    header("Location: $url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filter Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background-color: #cadedf;">
  <div class="container my-4">
    <h2>Filter Events</h2>
    <form method="GET" action="filter.php">
      <div class="row">
        <div class="col-md-4">
          <label for="sport" class="form-label">Sport</label>
          <input type="text" class="form-control" id="sport" name="sport" placeholder="Enter sport">
        </div>
        <div class="col-md-4">
          <label for="date" class="form-label">Date</label>
          <input type="date" class="form-control" id="date" name="date">
        </div>
        <div class="col-md-4 align-self-end">
          <button type="submit" class="btn btn-primary" name="filter">Apply Filter</button>
          <a href="index.php" class="btn btn-secondary">Clear</a>
        </div>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>