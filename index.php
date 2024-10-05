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

//=============== BEA HAR LAGT TILL ================//
$prevMonthDays = date('t', strtotime($prevMonth));
$lastDayOfMonth = date('N', strtotime($lastDateInMonth));
//=================================================//

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
//================== BEA HAR LAGT TILL OCH ÄNDRAT ==================//
$calStr .= "<tr class='weekdays'>\n";
$calStr .= "<td class='weekNum'>Week</td>\n";
//==================================================================//
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


//=================== BEA HAR LAGT TILL OCH ÄNDRAT =====================//
// Checks if dayNum is larger than 1 (is not a monday)
if ($dayNum > 1) {
    // Display week number at beginning of row
    $weekNumber = date('W', strtotime("$year-$month-$date")); // Get the week number
    $calStr .= "<td class='weekNum'>$weekNumber</td>\n"; // Add the week number cell

    // Gets dayNume - 1, counts down for as long as dayNum is larger than or equal to 1
    for ($i = $dayNum - 1; $i >= 1; $i--) {

        // Gets the value of "i" (dayNum - 1) e.g. 1 if the first day of the month is a tuesday
        // prevDate = the number of days in previous month e.g. 30, minus i (2 if first day of month falls on a tuesday)
        // plus 1 to display the remaining day of previous month, 30 - 2 + 1
        $prevDate = $prevMonthDays - $i + 1;
        $calStr .= "<td class='grayed-out'>$prevDate</td>\n";
    }
}

// Print current month days
for ($date = 1; $date <= $monthDays; $date++) {
    // Start a new row for each week
    if (date('N', strtotime("$year-$month-$date")) == 1) {
        // Display week number at beginning of row
        $weekNumber = date('W', strtotime("$year-$month-$date")); // Get the week number
        $calStr .= "<tr>\n";
        $calStr .= "<td class='weekNum'>$weekNumber</td>\n"; // Add the week number cell
    }

    // Print dates
    $calStr .= "<td class='monthDay'>$date</td>\n";

    // Check if it's Sunday and close the row
    if (date('N', strtotime("$year-$month-$date")) == 7) {
        $calStr .= "</tr>\n"; // Close the week row
    }
}

// Checks if last day of the month is less than 7 (not a Sunday)
if ($lastDayOfMonth < 7) {
    // Fill in days from the next month
    // lasDayOfMonth holds the dayNum of the last month day e.g 5 if the last date of the month falls on a Friday.
    // The loop then calculates upwards from e.g 5 until it gets to 7
    // Checks how many cells need to be filled after the last date of the current month
    for ($i = 1; $i <= 7 - $lastDayOfMonth; $i++) {
        $calStr .= "<td class='grayed-out'>$i</td>\n";
    }
    $calStr .= "</tr>\n";
}
//=====================================================================//

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
            <?= $monthName ?>
            <?=$year ?>
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
