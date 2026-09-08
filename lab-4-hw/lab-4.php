## 1. `create_database.php`


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Database</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Create Database</h2>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Database Name</label>

            <input type="text"
                   name="database_name"
                   class="form-control"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Create Database
        </button>

    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $databaseName = trim($_POST["database_name"]);

        $conn = new mysqli("localhost", "root", "");

        if ($conn->connect_error) {

            echo '<div class="alert alert-danger mt-3">
                    Connection failed: ' . $conn->connect_error . '
                  </div>';

        } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $databaseName)) {

            echo '<div class="alert alert-danger mt-3">
                    Invalid database name. Use only letters, numbers and underscores.
                  </div>';

        } else {

            $sql = "CREATE DATABASE `$databaseName`";

            if ($conn->query($sql) === TRUE) {

                echo '<div class="alert alert-success mt-3">
                        Database created successfully.
                      </div>';

            } else {

                echo '<div class="alert alert-danger mt-3">
                        Error: ' . $conn->error . '
                      </div>';
            }
        }

        $conn->close();
    }

    ?>

</div>

</body>
</html>




## 2. `create_table.php`


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Students Table</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Create Students Table</h2>

    <?php

    $conn = new mysqli("localhost", "root", "", "wis_lab");

    if ($conn->connect_error) {

        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(120) NOT NULL,
        department VARCHAR(80) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {

        echo '<div class="alert alert-success">
                Students table created successfully.
              </div>';

    } else {

        echo '<div class="alert alert-danger">
                Error: ' . $conn->error . '
              </div>';
    }

    $conn->close();

    ?>

</div>

</body>
</html>




## 3. `insert_student.php` — Challenge Task Included


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Add Student</h2>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form data
        $fullName = trim($_POST["full_name"]);
        $email = trim($_POST["email"]);
        $department = trim($_POST["department"]);

        // Create MySQLi connection
        $conn = new mysqli("localhost", "root", "", "wis_lab");

        // Check connection
        if ($conn->connect_error) {

            echo '<div class="alert alert-danger">
                    Connection failed: ' . $conn->connect_error . '
                  </div>';

        // Check empty fields
        } elseif (empty($fullName) || empty($email) || empty($department)) {

            echo '<div class="alert alert-danger">
                    All fields are required.
                  </div>';

        // Check email
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            echo '<div class="alert alert-danger">
                    Please enter a valid email address.
                  </div>';

        } else {

            // Challenge Task:
            // Create prepared statement
            $stmt = $conn->prepare(
                "INSERT INTO students (full_name, email, department)
                 VALUES (?, ?, ?)"
            );

            if ($stmt) {

                // Bind the values
                $stmt->bind_param(
                    "sss",
                    $fullName,
                    $email,
                    $department
                );

                // Execute the prepared statement
                if ($stmt->execute()) {

                    echo '<div class="alert alert-success">
                            Student added successfully.
                          </div>';

                } else {

                    echo '<div class="alert alert-danger">
                            Error: ' . $stmt->error . '
                          </div>';
                }

                // Close statement
                $stmt->close();

            } else {

                echo '<div class="alert alert-danger">
                        Prepare failed: ' . $conn->error . '
                      </div>';
            }
        }

        // Close connection
        if (isset($conn)) {
            $conn->close();
        }
    }

    ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">Full Name</label>

            <input type="text"
                   name="full_name"
                   class="form-control"
                   required>

        </div>


        <div class="mb-3">

            <label class="form-label">Email</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   required>

        </div>


        <div class="mb-3">

            <label class="form-label">Department</label>

            <input type="text"
                   name="department"
                   class="form-control"
                   required>

        </div>


        <button type="submit" class="btn btn-primary">
            Save Student
        </button>

        <button type="reset" class="btn btn-secondary">
            Clear
        </button>

    </form>

</div>

</body>
</html>
