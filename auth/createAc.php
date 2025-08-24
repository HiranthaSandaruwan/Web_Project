<?php
    include '../db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $u = $_POST['username'];
        $p = $_POST['password'];
        $role='user';
        $created_at = date('Y-m-d H:i:s');

        $check = $mysqli->prepare("SELECT user_id FROM users WHERE username = ?");
        $check->bind_param("s", $u);
        $check->execute();
        $check->store_result();

        $hashed_password = SHA1($p);

        if($check->num_rows > 0){
            echo "<script>alert('User already exists!');</script>";
        } else {
            $stmt=$mysqli->prepare("INSERT INTO users(username,password,role,created_at) values(?,?,?,?)");
            $stmt->bind_param("ssss", $u, $hashed_password, $role, $created_at);
             if($stmt->execute()){
            echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error creating account. Please try again.');</script>";
            }
            $stmt->close();
        }  
    }
?>
<html>
<head>
    <title>Create Account - RepairTracker</title>
    <link rel="stylesheet" href="../assets/css/unified-styles.css">
    <style>
        .border{
            border: 1px solid #0a0909ff;
            border-radius: 8px;
            padding: 20px;
            margin: 50px auto;
            max-width: 400px;
            box-shadow: 0px 4px 12px 16px rgba(0, 0, 0, 0.1);
            width:450px;
            height: 400px;
        }
        .item-center{
            display: grid;
            place-items: center;
            height: 100vh;
        }
        .form{         
            display:grid;
            place-items: center;
            gap: 10px;
        }
        .input-group{
            position: relative;
            width: 250px;
        }
        .input-group img{
            position: absolute;
            top: 50%;
            left: 10px;
            width: 20px;
            height: 20px;
            transform: translateY(-50%);
        }
        .form .input-group input{
            padding: 10px 10px 10px 40px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 7px;
            border: 1px solid #ccc;
            outline: none;
        }
        .form .input-group input.error {
            border: 2px solid rgb(241, 83, 83);
            background-color: #ffeaea;
        }
        .title{
            color:white;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 30px;
            margin-bottom: 20px;
        }
        button{
            padding: 10px 20px;
            border-radius: 7px;
            border: none;
            background: #0dc739;
            color: #fff;
            cursor: pointer;
        }
        .button{
            text-align: center;
            margin-top: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="item-center">
        <div class="border">
            <h2 class="title">Create Account</h2>
            <form action="" method="post" class="form" onsubmit="return validate()">
                <div class="input-group">
                    <img src="../Images/user.png" alt="user">
                    <input type="email" name="username" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <img src="../Images/padlock.png" alt="password">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <img src="../Images/padlock.png" alt="repassword">
                    <input type="password" id="repassword" placeholder="Re-enter Password" required>
                </div>
                <div class="button">
                    <button type="submit">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validate(){
            var password = document.getElementById("password").value;
            var repassword = document.getElementById("repassword"); 

            if(password !== repassword.value){
                repassword.classList.add("error");
                return false; 
            }
            return true;  
        }

        // when focusing again // remove error style
        document.getElementById("repassword").addEventListener("focus", function(){
            this.classList.remove("error");
        });
    </script>
</body>
</html>
