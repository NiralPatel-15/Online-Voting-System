<?php
include 'includes/session.php';

function generateTable($conn)
{
    $output = '<h2 align="center">Election Results</h2>';
    $output .= '<h4 align="center">Tally Result</h4>';
    $output .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';

    $sql = "SELECT * FROM positions ORDER BY priority ASC";
    $query = $conn->query($sql);

    while ($row = $query->fetch_assoc()) {
        $id = $row['id'];
        $output .= '<tr><td colspan="3" align="center" style="font-size:15px;"><b>' . $row['description'] . '</b></td></tr>';
        $output .= '<tr><td width="60%"><b>Candidates</b></td><td width="20%"><b>Votes</b></td><td width="20%"><b>Status</b></td></tr>';

        $cquery = $conn->query("SELECT * FROM candidates WHERE position_id = '$id' ORDER BY lastname ASC");
        if ($cquery->num_rows == 0) {
            $output .= '<tr><td colspan="3">No candidates found.</td></tr>';
        }

        $maxVotes = 0;
        $winners = [];
        while ($crow = $cquery->fetch_assoc()) {
            $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id = '" . $crow['id'] . "'");
            $votes = $vquery->num_rows;

            if ($votes > $maxVotes) {
                $maxVotes = $votes;
                $winners = [$crow];
            } elseif ($votes == $maxVotes) {
                $winners[] = $crow;
            }
        }

        $cquery = $conn->query("SELECT * FROM candidates WHERE position_id = '$id' ORDER BY lastname ASC");
        while ($crow = $cquery->fetch_assoc()) {
            $vquery = $conn->query("SELECT * FROM votes WHERE candidate_id = '" . $crow['id'] . "'");
            $votes = $vquery->num_rows;

            $isWinner = in_array($crow, $winners);
            $status = $isWinner ? '<b style="color:green;">Winner</b>' : '';

            $output .= '<tr><td>' . $crow['firstname'] . ' ' . $crow['lastname'] . '</td><td>' . $votes . '</td><td>' . $status . '</td></tr>';
        }
    }

    $output .= '</table>';
    return $output;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Election Results</title>
</head>

<body>
    <?php echo generateTable($conn); ?>
    <button onclick="window.print()">Print</button>
</body>

</html>