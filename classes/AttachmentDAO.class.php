<?php 

class AttachmentDAO{
	public function createAttachment($path, $ticketId, $name){
		$db = Database::getInstance();

		$db->query("INSERT INTO 
						tb_tickets_attachments
					VALUES
					(
						null 
					,	?
					,	?
					,	?
					)"
					,
					array($ticketId, $path, $name)
					);
	}

	public function getAttachmentsByTicketId($id){
		$db = Database::getInstance();

		$db->query("SELECT 
						*
					FROM
						tb_tickets_attachments
					WHERE 
						id_ticket = ?"
					,
					array($id)
					);

		return $db->getResults();
	}

	public function deleteAttachment($id){
		$db = Database::getInstance();

		$db->query("SELECT *
					FROM 
						tb_tickets_attachments 
					WHERE 
						id_attachment = ?"
						,
						array($id)
						);

		$attachment = $db->getResults();
		unlink($attachment[0]->path_attachment);

		$db->query("DELETE FROM 
						tb_tickets_attachments
					WHERE
						id_attachment = ?"
						,
						array($id)
						);
	}

	public function createProductAttachment($path, $ticketId, $name) {
		$db = Database::getInstance();
		
			$db->query("INSERT INTO 
							tb_products_attachments
						VALUES
						(
							null 
						,	?
						,	?
						,	?
						)"
						,
						array($ticketId, $path, $name)
						);
	}
	
	public function getAttachmentsByProductTicketId($id){
		$db = Database::getInstance();

		$db->query("SELECT 
						*
					FROM
						tb_products_attachments
					WHERE 
						id_ticket = ?"
					,
					array($id)
					);

		return $db->getResults();
	}
	
	public function deleteProductAttachment($id){
		$db = Database::getInstance();

		$db->query("SELECT *
					FROM 
						tb_products_attachments 
					WHERE 
						id_attachment = ?"
						,
						array($id)
						);

		$attachment = $db->getResults();
		unlink($attachment[0]->path_attachment);

		$db->query("DELETE FROM 
						tb_products_attachments
					WHERE
						id_attachment = ?"
						,
						array($id)
						);
	}
}