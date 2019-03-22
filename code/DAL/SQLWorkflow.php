<?php namespace Assign3;

/**
 * MySQL implementation of WorkflowDB methods.
 */
class SQLWorkflow implements WorkflowDB
{
    /**
     * Get an array of all workflows.
     */
    public function getWorkflows() {
        $conn = Conn::getDbConnection();
        $sql = "SELECT w.id, w.name, w.description ";
        $sql .= "FROM workflow w ";
        $sql .= "ORDER BY w.name;";
        $result = mysqli_query($conn, $sql);

        $workflows = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $workflow = new Workflow($row['id'], $row['name'], $row['description']);
            array_push($workflows, $workflow);
        }

        return $workflows;
    }

    /**
     * Get an individual workflow
     */
    public function getWorkflow($workflow_id) {
        if (!isset($workflow_id)) {
            return null;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT w.id, w.name, w.description ";
        $sql .= "FROM workflow w ";
        $sql .= "WHERE w.id = ".$workflow_id." ";
        $sql .= "ORDER BY w.name;";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        $workflow = null;
        if ($count == 1) {
            $workflow = new Workflow($row['id'], $row['name'], $row['description']);
        }

        $this->getProcesses($workflow);

        return $workflow;
    }

    /**
     * Set all processes for a given workflow.
     */
    public function getProcesses($workflow) {
        if (!isset($workflow)) {
            return false;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT p.id, p.name, p.active, wp.sequence, wp.estimated_time ";
        $sql .= "FROM process p INNER JOIN workflow_process wp ON p.id = wp.process_id ";
        $sql .= "WHERE wp.workflow_id = ".$workflow->id." ";
        $sql .= "ORDER BY wp.sequence;";
        $result = mysqli_query($conn, $sql);

        $workflow->processes = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $process = new Process($row['id'], $row['name'], $row['active']);
            $process->workflowSequence = $row['sequence'];
            $process->workflowEstimateTime = $row['estimated_time'];
            array_push($workflow->processes, $process);
        }

        return true;
    }
}
