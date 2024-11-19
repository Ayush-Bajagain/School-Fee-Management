   <style>
       @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');




       * {
           margin: 0;
           padding: 0;
           box-sizing: border-box;
       }

       body {
           font-family: Arial, sans-serif;
           min-height: 100vh;
           overflow-x: hidden;
       }

       .container {
           display: flex;
           height: 100vh;
           flex-direction: column;
       }

       .header {
           background-color: #333;
           color: white;
           display: flex;
           justify-content: space-between;
           align-items: center;
           padding: 10px 20px;
       }

       .logo {
           width: 75px;
           height: 75px;
           overflow: hidden;
           margin-left: 50px;
           border: 1px solid #ffffff33;
           border-radius: 50%;
       }

       .logo img {
           width: 100%;


       }

       .title h1 {
           font-size: 24px;
           margin-left: 20px;
       }

       .date-time {
           text-align: right;
       }



       .wrapper {
           width: 100%;
           height: 100vh;
           display: flex;
       }

       .sidebar {
           background-color: #2b2b2b;
           position: relative;
           color: white;
           padding: 20px;
           width: 200px;
           height: 100%;
           display: flex;
           flex-direction: column;
       }

       .sidebar .welcome {
           margin-bottom: 20px;
           font-weight: bold;
           color: yellow;
       }

       .menu ul {
           list-style: none;
       }

       .menu ul li {
           margin-bottom: 20px;
           width: 100%;
           height: 40px;
           display: flex;
           justify-content: center;
           align-items: center;

       }

       .menu>ul>li a {
           color: white;
           text-decoration: none;
           position: relative;
           font-size: 18px;
       }

       .active {
           color: royalblue !important;
       }

       .menu ul li a:hover {
           color: royalblue;
       }


       #logout-bnt {
           position: absolute;
           bottom: 0;
           right: 10px;
           width: 90%
       }


       .main-content {
           padding: 50px;
           background-color: #fff;
           flex: 1;
       }





       /*+++++++++++++++++++++++++++++++++++++ Content part +++++++++++++++++++++++++++++++++*/


       .main-content .go-back {
           width: 150px;
           height: 40px;
           border: none;
           border-radius: 10px;
           background-color: royalblue;
           color: #fff;
           font-size: 16px;
           font-family: "poppins";
           cursor: pointer;
           position: relative;
           top: -30px;
       }



       .main-content form {
           width: 100%;
           border: 2px solid rosybrown;
           border-radius: 4px;
           padding: 20px;
           font-family: "poppins";
           position: relative;

       }

       form .photo {
           width: 150px;
           height: 180px;
           border: 1px dashed #33333399;
           position: absolute;
           right: 20px;

       }

       

       form .photo input {
           width: fit-content;
           opacity: 80%;

           cursor: pointer;
       }



       form .box {
           width: 100%;
           height: 40px;
           margin: 50px 0;
           display: flex;
           justify-content: space-between;
           align-items: center;
       }

       form .box span {
           width: 100%;
           height: 100%;
       }

       .box input {
           margin-left: 30px;
           font-size: 16px;
           letter-spacing: 1px;
           opacity: 90%;
           height: 100%;
           width: 60%;
           outline: none;
           border: none;
           background-color: transparent;
           border-bottom: 2px solid #00000045;
       }

       .gender {
           margin: 40px 0;
       }

       .gender span {
           margin-left: 50px;
       }

       .gender span label {
           margin-right: 20px;
       }


       .program span {
           width: 300px;
           height: 40px;
       }

       .program span label {
           margin-right: 50px;
       }

       .program span select {
           border: none;
           border: 1px solid #00000045;
           padding: 12px;

           outline: none;
           width: 300px;
           height: 40px;
       }




       #submit-btn {
           width: 200px;
           height: 40px;
           border: none;
           outline: none;
           background-color: royalblue;
           border-radius: 10px;
           opacity: 90%;
           color: #fff;
           font-size: 16px;
           cursor: pointer;
       }

       #sumit-btn:hover {
           opacity: 100%;
       }
   </style>