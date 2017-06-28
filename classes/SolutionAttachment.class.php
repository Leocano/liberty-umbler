<?php 

class SolutionAttachment{
	private $id_solution_attachment;
	private $id_solution;
	private $name_solution_attachment;
	private $path_solution_attachment;

	public function __construct($id_solution_attachment, $id_solution, $name_solution_attachment, $path_solution_attachment){
		$this->id_solution_attachment = $id_solution_attachment;
		$this->id_solution = $id_solution;
		$this->name_solution_attachment = $name_solution_attachment;
		$this->path_solution_attachment = $path_solution_attachment;
	}

	public function getIdSolutionAttachment(){
		return $this->id_solution_attachment;
	}

	public function getIdSolution(){
		return $this->id_solution;
	}

	public function getNameSolutionAttachment(){
		return $this->name_solution_attachment;
	}

	public function getPathSolutionAttachment(){
		return $this->path_solution_attachment;
	}
}