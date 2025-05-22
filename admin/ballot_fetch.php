<?php
include 'includes/session.php';
include 'includes/slugify.php';

// Fetch latest election details securely
$stmt = $conn->prepare("SELECT * FROM elections ORDER BY id DESC LIMIT 1");
$stmt->execute();
$equery = $stmt->get_result();
$election = $equery->fetch_assoc();

$electionTitle = $election['title'];
$electionStart = date('F j, Y, g:i a', strtotime($election['start_time']));
$electionEnd = date('F j, Y, g:i a', strtotime($election['end_time']));

// Fetch all positions and candidates with a single query
$sql = "SELECT positions.*, candidates.id AS candidate_id, candidates.firstname, candidates.lastname, candidates.photo 
        FROM positions 
        LEFT JOIN candidates ON positions.id = candidates.position_id
        ORDER BY positions.priority ASC";

$query = $conn->query($sql);
$num = 1;
$output = '';
$positions = [];

// Group candidates under their respective positions
while ($row = $query->fetch_assoc()) {
    $positions[$row['id']]['description'] = $row['description'];
    $positions[$row['id']]['max_vote'] = $row['max_vote'];
    $positions[$row['id']]['priority'] = $row['priority'];
    
    if (!empty($row['candidate_id'])) {
        $positions[$row['id']]['candidates'][] = [
            'id' => $row['candidate_id'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'photo' => !empty($row['photo']) ? '../images/' . $row['photo'] : '../images/profile.jpg'
        ];
    }
}

// Flag to display election details once
$displayElectionDetails = true;

foreach ($positions as $position_id => $position) {
    $inputType = ($position['max_vote'] > 1) ? 'checkbox' : 'radio';
    $inputName = slugify($position['description']) . ($position['max_vote'] > 1 ? '[]' : '');
    
    // Generate candidate list
    $candidateList = '';
    if (!empty($position['candidates'])) {
        foreach ($position['candidates'] as $candidate) {
            $candidateList .= '
                <li>
                    <input type="' . $inputType . '" class="flat-red ' . slugify($position['description']) . '" name="' . $inputName . '">
                    <button class="btn btn-primary btn-sm btn-flat clist"><i class="fa fa-search"></i> Platform</button>
                    <img src="' . $candidate['photo'] . '" height="100px" width="100px" class="clist">
                    <span class="cname clist">' . $candidate['firstname'] . ' ' . $candidate['lastname'] . '</span>
                </li>';
        }
    }

    // Instructions
    $instructions = ($position['max_vote'] > 1) ? "You may select up to " . $position['max_vote'] . " candidates" : "Select only one candidate";

    // Disable Up/Down buttons if necessary
    $updisable = ($position['priority'] == 1) ? 'disabled' : '';
    $downdisable = ($position['priority'] == count($positions)) ? 'disabled' : '';

    // Election Info (Displayed Once)
    $electionInfo = '';
    if ($displayElectionDetails) {
        $electionInfo = '<h3 class="box-title text-center"><b>' . $electionTitle . '</b></h3>
            <div style="text-align: center; font-size: 14px; color: #555; background: #f5f5f5; padding: 5px 10px; border-radius: 5px; margin-top: 10px;">
                <b>Start:</b> ' . $electionStart . ' | <b>End:</b> ' . $electionEnd . '
            </div>';
        $displayElectionDetails = false;
    }

    // Construct ballot section
    $output .= '
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid" id="position-' . $position_id . '">
                    <div class="box-header with-border text-center">
                        ' . $electionInfo . '
                        <div class="pull-right box-tools" style="margin-right: 20px;">
                            <button type="button" class="btn btn-default btn-sm moveup" data-id="' . $position_id . '" ' . $updisable . '><i class="fa fa-arrow-up"></i></button>
                            <button type="button" class="btn btn-default btn-sm movedown" data-id="' . $position_id . '" ' . $downdisable . '><i class="fa fa-arrow-down"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <h4><b>' . $position['description'] . '</b></h4>
                        <p>' . $instructions . '
                            <span class="pull-right">
                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="' . slugify($position['description']) . '"><i class="fa fa-refresh"></i> Reset</button>
                            </span>
                        </p>
                        <div id="candidate_list">
                            <ul>' . $candidateList . '</ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    
    $num++;
}

// Return the generated ballot as JSON
echo json_encode($output);
?>
