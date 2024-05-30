<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';


$query = "SELECT tblclass.className, tblclassarms.classArmName, tblstudents.firstName, tblstudents.lastName, tblstudents.otherName
  FROM tblstudents
  INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
  INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
  WHERE tblstudents.Id = '$_SESSION[userId]'";

$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();


$query1 = "SELECT COUNT(*) as rowCount FROM `tblattendance`
    WHERE `admissionNo` = '$_SESSION[admissionNumber]' AND `status` = 1";
$rsk = $conn->query($query1);
$numk = $rsk->num_rows;
$rrwk = $rsk->fetch_assoc();

// Get the total rowCount without the WHERE condition
$query2 = "SELECT COUNT(*) as totalRowCount FROM `tblattendance` WHERE `admissionNo` = '$_SESSION[admissionNumber]'";
$rsk2 = $conn->query($query2);
$numk2 = $rsk2->num_rows;
$rrwk2 = $rsk2->fetch_assoc();





$querey3 = "SELECT marks FROM `tblmarks` WHERE `admissionID` = '$_SESSION[admissionNumber]'";
$rsk3 = $conn->query($querey3);
$numk3 = $rsk3->num_rows;
$rrwk3 = $rsk3->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./StudentPortalDashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />


  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Abhaya Libre:wght@400&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Acme:wght@400&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aclonica:wght@400&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aleo:wght@700&display=swap" />
</head>

<body>
  <div class="student-portal-dashboard">
    <nav class="navigation-bar">
      <div class="navigation1">
        <div class="rectangle-parent">
          <img class="college-logo" loading="lazy" alt="" src="./public/dypiu-logo.png" />
        </div>

        <a href="#" class="active nav-items">Dashboard</a>
        <a href="#" class="nav-items">Attendence</a>
        <a href="#" class="nav-items">Payment Info</a>
        <a href="#" class="nav-items">Courses</a>
        <a href="#" class="nav-items">Result</a>
        <a href="#" class="nav-items">Notice</a>
        <a href="#" class="nav-items">Schedule</a>
      </div>

      <div class="logout-icon">
        <img class="logout-icon1" loading="lazy" alt="" src="./public/logout.svg" />
        <a href="logout.php" class="logout">Logout</a>
      </div>
    </nav>
    <main class="main-section">
      <header class="main-header">
        <div class="container">
          <input placeholder='Search...' class='js-search' type="text">
          <i class="fa fa-search"></i>
        </div>

        <div class="profile">
          <img class="bell-ringing-icon" loading="lazy" alt="" src="./public/bellringing.svg" />
          <div class="wrapper-no-application-parent">

            <div class="application-name-label">
              <div class="john-doe"><?php echo $_SESSION['firstName'] ?></div>
              <div class="rd-year"><?php echo $rrw['className'] ?></div>
            </div>
            <div class="wrapper-no-application">
              <a href="logout.php" class="no-application"><img class="no-application-icon" loading="lazy" alt=""
                  src="./public/frame-19@2x.png" /></a>


            </div>
          </div>

        </div>
      </header>
      <div class="welcome">
        <div class="frame-parent">
          <div class="welcome-messages">
            <div class="frame-heading-parent">
              <h1 class="frame-heading">Welcome back, <span><?php echo $_SESSION['firstName'] ?></span></h1>
              <div class="frame-para">
                Always stay updated in your student portal
              </div>
            </div>
          </div>
          <img class="college-student-icon" loading="lazy" alt="" src="./public/5-college-student@2x.png" />
        </div>
        <div class="progress-and-attendance">
          <div class="attendance-parent">
            <div class="sub-headers sub-headers-label">Progress</div>
            <div class="content-wrapper">
              <div class="progress-bar"><?php
              if ($rrwk3['marks'] == 0) {
                echo "0";
              } else {
                echo $rrwk3['marks'];
              } ?></div>
            </div>
          </div>
          <div class="attendance-parent">
            <div class="sub-headers sub-headers-label">Attendance</div>
            <div class="content-wrapper">
              <div class="progress-bar"><?php echo $rrwk['rowCount'] . "/" . $rrwk2['totalRowCount'] ?></div>
            </div>
          </div>

        </div>
        <div class="enroll-courses">
          <div class=" sub-headers">
            <h4 class=" sub-headers-label">Enrolled Courses</h4>
            <a href="#" class=" sub-headers-link">See all</a>
          </div>
          <div class="course-overview">

            <div class="course-status">
              <div class="course-list">
                <div class="course-items">
                  <div class="courses-heading">
                    Database Management System
                  </div>
                  <button class="view-buttons">
                    <div class="view">View</div>
                  </button>
                </div>
              </div>
              <img class="icon-container" loading="lazy" alt="" src="./public/icon-container@2x.png" />
            </div>
            <div class="course-status">
              <div class="course-list">
                <div class="course-items">
                  <div class="courses-heading">
                    Introduction to Intelligent System
                  </div>
                  <button class="view-buttons">
                    <div class="view">View</div>
                  </button>
                </div>
              </div>
              <img class="icon-container" loading="lazy" alt="" src="./public/icon-container@2x.png" />
            </div>
          </div>

        </div>

      </div>
      <div class="instructor-payments">
        <div class="wrapper-instructors">
          <img class="instructors-icon" alt="calender" src="./public/instructors@2x.png" />
        </div>
        <div class="course-instructors-header-parent">
          <div class="course-instructors-header">
            <div class="course-instructors">Professors</div>
            <a href="#" class="instructor-see">See all</a>
          </div>
          <div class="instructors-list">
            <div class="instructor-avatars">
              <img class="instructor-avatars-child" loading="lazy" alt="" src="./public/ellipse-16@2x.png" />

              <img class="instructor-avatars-child" loading="lazy" alt="" src="./public/ellipse-17@2x.png" />

              <img class="instructor-avatars-child" loading="lazy" alt="" src="./public/ellipse-18@2x.png" />

              <img class="instructor-avatars-child" loading="lazy" alt="" src="./public/ellipse-18@2x.png" />
            </div>
          </div>
        </div>
        <div class="payment-info-header-parent">
          <div class="payment-info-header">
            <h4 class="payment-info1">Payment Info</h4>
            <a href="#" class="payment-see">See all</a>
          </div>
          <div class="payment-details">
            <div class="payment-status">
              <div class="payment-icons">
                <img class="file-pen" loading="lazy" alt="" src="./public/file--pen.svg" />
              </div>
              <div class="payment-amounts">
                <div class="amount-labels">
                  $ 10,000
                </div>
                <div class="total-payable">Total Payable</div>
              </div>
            </div>
            <div class="payment-status">
              <div class="payment-icons">
                <img class="file-pen" loading="lazy" alt="" src="./public/file--pen.svg" />
              </div>
              <div class="payment-amounts">
                <div class="amount-labels">
                  $ 10,000
                </div>
                <div class="total-payable">Total Paid</div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </main>
  </div>
</body>

</html>