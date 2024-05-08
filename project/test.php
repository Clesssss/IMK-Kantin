<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>Show Modal Example</title>
</head>
<body>

  <!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    Open modal
  </button>

  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-e3IYwq6FU5b5i8cJbFtGxpZMnA3d3xYVzKlJ1DAwnrZwFmxC8FqELMEHux5DBN8U" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-cN9URWh/dN1z7fLEFzIBCBzVQH7rSNto2z+JGKEb8UBoGJI7yj7kDO2Mwvdc9bQ" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-GLhlTQ8iKZ6nFZnL6eVKckngdB+Di1YKq6oZa9M5v5DIIx5M1oI3ZB9Tq6zH2tN" crossorigin="anonymous"></script>

</body>
</html>