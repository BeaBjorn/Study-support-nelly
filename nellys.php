<?php
include('./config/config.php');

$title = 'Photo Calendar';

include('./view/header.php');

$date = $_GET['date'] ?? date('Y-m-d');
$timestamp = strtotime($date);

//All parts of month
$year = date('Y', $timestamp);
$month = date('m', $timestamp);
$monthName = date('F', $timestamp);
$monthDays = date('t', $timestamp);
$date = date('d', $timestamp);
$weekday = date('l', $timestamp);
$dayNum = date('N', $timestamp);
$yearDays = date('z', $timestamp) + 1;
$yearWeek = date('W', $timestamp);

//Important date
$dateStr = date('Y-m-d', $timestamp);
$firstDateInMonth = date('Y-m-01', $timestamp);
$lastDateInMonth = date("Y-m-$monthDays", $timestamp);

//Next & Previous month
$timestampPreviousMonth = strtotime($firstDateInMonth . "- 1 day");
$timestampNextMonth = strtotime($lastDateInMonth . "+ 1 day");

$prevMonth = date('Y-m-01', $timestampPreviousMonth);
$nextMonth = date('Y-m-01', $timestampNextMonth);


//$dateStr = "";
//if ($date) {
//    $dateStr = htmlentities($date);
//}
// Define images for each month

// Extract details about the date, if it is a valid date
$timestamp = null;
if ($date) {
    $timestamp = strtotime($date);
}

if ($timestamp) {
    $dateStr = date('Y-m-d', $timestamp);
    $monthStr = date('F', $timestamp);
    $monthDaysStr = date('t', $timestamp);
    $weekStr = date('W', $timestamp);
    $dayStr = date('l', $timestamp);
}

$calStr = "";
$aTimestamp = strtotime($firstDateInMonth);
$bTimestamp = strtotime('Monday this week');
$calStr .= "<tr class='calBorder'>\n";




$monthImages = [
    '01' => 'img/januari.jpg',
    '02' => 'img/february.jpg',
    '03' => 'img/march.jpg',
    '04' => 'img/april.jpg',
    '05' => 'img/may.jpg',
    '06' => 'img/juni.jpg',
    '07' => 'img/juli.jpg',
    '08' => 'img/august.jpg',
    '09' => 'img/september.jpg',
    '10' => 'img/october.jpg',
    '11' => 'img/november.jpg',
    '12' => 'img/december.jpg',
];

// Get the image for the current month
$currentImage = $monthImages[$month] ?? 'images/default.jpg';

$calStr .= "<img src='$currentImage' alt='$monthName Image' style='width:100%;'>";
$calStr .= "<tr>\n";
for ($i = 1; $i <= 7; $i++) {
    $aDayNum = date('N', $bTimestamp); // Numeric day of the week (1 = Monday, 7 = Sunday)
    $aWeekday = date('l', $bTimestamp); // Full textual representation of the day

    if ($aDayNum == 7) {
        $calStr .= "<td class='sundays'>$aWeekday</td>\n"; // Highlight Sunday
    } else {
        $calStr .= "<td class='weekDay'>$aWeekday</td>\n"; // Other weekdays
    }

    $bTimestamp = strtotime('+1 day', $bTimestamp); // Move to the next day
}
$calStr .= "</tr>\n";

// Empty cells for days before the first day of the month
if($monthDays < 1) {
    for ($i = 1; $i < $monthDays; $i++) {
        $calStr .= "<td></td>\n"; // Empty cells
    }
}

for ($date = 1; $date <= $monthDays; $date++) {
    // Print day of the month
    $calStr .= "<td class='monthDay'>$date</td>\n";

    // Move to the next day
    $aDayNum = date('N', strtotime("$year-$month-$date")); // Numeric representation of the day of the week (1 = Monday, 7 = Sunday)

    // Check if it's Sunday (end of the week) and close the row
    if ($aDayNum == 7) {
        $calStr .= "</tr>\n";
        if ($date < $monthDays) {
            $calStr .= "<tr class='calBorder'>\n"; // Start new week row
        }
    }
}
// Empty cells after the last day of the month (if the last day is not Sunday)
if ($aDayNum != 7) {
    for ($i = $aDayNum + 1; $i <= 7; $i++) {
        $calStr .= "<td></td>\n"; // Empty cells
    }
    $calStr .= "</tr>\n";
}

//for ($i = 1; $i <= $monthDays; $i++) {
//    $aDate = date('d', $aTimestamp);
//    $aWeekday = date('l', $aTimestamp);
//    $aYearDay = date('z', $aTimestamp) + 1;
//    $aYearWeek = date('W', $aTimestamp);
//    $aDayNum = date('N', $aTimestamp);

//    $calStr .= "<td class='monthNum'>$aDate</td>\n";
//    $calStr .= "<td class='yearDay'>$aYearDay</td>\n";
//    if ($aDayNum == 1 || $aDate == 1) {
//        $calStr .= "<td class='yearWeek'>$aYearWeek</td>\n";
//    }

//    $calStr .= "</tr>\n";
//    $aTimestamp = strtotime("+ 1 day", $aTimestamp);
//}

?>
<main class="main">
    <article class="article">
        <form action="#" method="get">
            <p class="inp">
                Datum:
                <input type="text" value="<?= $dateStr ?>"
                    name="date" placeholder="Skriv in ett datum">
            </p>

            <p class="inp">
                <input class="button" type="submit" value="Search" name="doit">
            </p>

            <output>
                <?php if ($dateStr) : ?>
                <?php endif; ?>
            </output>
        </form>
        <h2 class="monthyear">
            <?= $monthName ?><?=$year ?>
        </h2>
        <div class="prevNextWrapper">
            <div class="prev">
                <p><a href="?date=<?= $prevMonth ?>">&#10508;
                        Previous</a></p>
            </div>
            <div class="next">
                <p><a href="?date=<?= $nextMonth ?>">Next
                        &#10509;</a></p>
            </div>
        </div>
        <table>
            <?= $calStr ?>
        </table>
    </article>
</main>


<?php include('./view/footer.php') ?>
