<!-- Preview Modal -->
<div class="modal fade" id="preview_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Vote Preview</h4>
      </div>
      <div class="modal-body">
        <div id="preview_body"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
          <i class="fa fa-close"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Candidate Platform Modal -->
<div class="modal fade" id="platform">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><b><span class="candidate"></span></b></h4>
      </div>
      <div class="modal-body">
        <p id="plat_view"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
          <i class="fa fa-close"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- View Ballot Modal -->
<div class="modal fade" id="view">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Your Votes</h4>
      </div>
      <div class="modal-body">
        <?php
        include 'includes/session.php';
        include 'includes/conn.php';

        if (!isset($_SESSION['voter'])) {
            echo "<p class='text-danger text-center'>Voter session not found.</p>";
            exit;
        }

        $id = $_SESSION['voter'];
        $sql = "SELECT candidates.firstname AS canfirst, candidates.lastname AS canlast, positions.description 
                FROM votes 
                LEFT JOIN candidates ON candidates.id = votes.candidate_id 
                LEFT JOIN positions ON positions.id = votes.position_id 
                WHERE votes.voters_id = '$id' 
                ORDER BY positions.priority ASC";

        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                echo "
                    <div class='row votelist'>
                      <span class='col-sm-4'><b>" . htmlspecialchars($row['description']) . ":</b></span> 
                      <span class='col-sm-8'>" . htmlspecialchars($row['canfirst'] . " " . $row['canlast']) . "</span>
                    </div>
                ";
            }
        } else {
            echo "<p class='text-center text-danger'>No votes found.</p>";
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
          <i class="fa fa-close"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery Script for Vote Preview -->
<script>
  $(document).ready(function () {
      $('.previewBtn').click(function () {
          var previewContent = $('#ballotForm').serializeArray();
          var html = "<ul>";
          $.each(previewContent, function (i, field) {
              html += "<li><b>" + field.name.replace("vote_", "Position ") + ":</b> " + field.value + "</li>";
          });
          html += "</ul>";
          $('#preview_body').html(html);
      });
  });
</script>
