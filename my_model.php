<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
  
Class Default_setting_model extends CI_Model
{
	function __construct()
	{
	  parent::__construct();
	}

	private $table = null;
	private $condition = null;
	private $join = null;
	private $right_join = null;
	private $left_join = null;
	private $field = null;
	private $select_as_array = null;
	private $limit = null;
	private $offset = 0;
	private $groupby = null;
	private $order_by = null;
	private $or_where_in = null;
	private $having = null;
	/* This function used to set private variables which is required for DB queries*/

	public function set_query_data($data){

		if( count($data) ){
			
			$this->table = ( isset($data['table']) ) ? $data['table'] :  null;

			if($this->table == null){
				return "missing table name";
			}

			$this->condition       = ( isset($data['condition']) ) ? $data['condition'] :  null;

			$this->field           = ( isset($data['field']) ) ? $data['field'] :  null;

			$this->select_as_array = ( isset($data['select_as_array']) ) ? $data['select_as_array'] : false;

			$this->join            = ( isset($data['join']) ) ? $data['join'] :  null;
			$this->right_join      = ( isset($data['right_join']) ) ? $data['right_join'] :  null;
			$this->left_join       = ( isset($data['left_join']) ) ? $data['left_join'] :  null;
			$this->groupby         = (isset($data['group_by'])) ? $data['group_by'] : null;
			$this->order_by        = (isset($data['order_by'])) ? $data['order_by'] : null;
			$this->having        = (isset($data['having'])) ? $data['having'] : null;
			$this->limit           = (isset($data['limit'])) ? $data['limit'] : null;
			$this->offset           = (isset($data['offset'])) ? $data['offset'] : 0;
			//$this->or_where_in_field = (isset($data['or_where_in_field'])) ? $data['or_where_in_field'] : null;
			$this->or_where_in     = (isset($data['or_where_in'])) ? $data['or_where_in'] : null;
		}
		else{

			return "missing parmeters";
		}
	}

	public function select(){

		if( !$this->db->table_exists( $this->table ) )
		{
			return;
		}
			
		
		$this->db->select( $this->field )->from( $this->table );

		if( $this->join ){ 
		// Here check for join is set if it is join operation is performed
			foreach ( $this->join as $table_name => $join_condition ) {
				$this->db->join( $table_name , $join_condition);
			}
		}

		if( $this->right_join ){ 
		// Here check for join is set if it is join operation is performed
			foreach ( $this->right_join as $table_name => $join_condition ) {
				$this->db->join( $table_name , $join_condition, 'RIGHT');
			}
		}

		if( $this->left_join ){ 
		// Here check for join is set if it is join operation is performed
			foreach ( $this->left_join as $table_name => $join_condition ) {
				$this->db->join( $table_name , $join_condition, 'LEFT');
			}
		}

		if( $this->condition ){
			// Here check for condition if condition is set where condition is applied
			$this->db->where( $this->condition );

		}

		if($this->or_where_in){
			$this->db->where_in( $this->or_where_in['or_where_in_field'], $this->or_where_in['where_in_array'] );
		}

		if($this->groupby){
			foreach ( $this->groupby as  $groupby ) {
				$this->db->group_by( $groupby );
			}
			
		}

		if( $this->having ){
	      // Here applying condition with having clause
	      $this->db->having( $this->having );
	    }
	    
		if($this->order_by){
			foreach ( $this->order_by as  $order_by ) {
				$this->db->order_by( $order_by );
			}
			
		}

		if( $this->limit ){
			 $this->db->limit( $this->limit, $this->offset );
			//$this->db->limit( $this->limit );
		}

		$result = $this->db->get();

		/*Here check for num rows returned by query if num rows <=1 then row() is returned otherwise result is returned*/

		if( $result->num_rows <= 1 )
		{
			return $result->row();
		} else {
			if ($this->select_as_array) {
				return $result->result_array();
			} else {
				return $result->result();
			}
		}
	}

	public function insert( $data = '' ){

		return $this->db->insert( $this->table , $data );
	}

	public function delete(){

		return $this->db->delete( $this->table , $this->condition );
	}

	public function update( $data ){
		 $this->db->where( $this->condition );
		 return $this->db->update( $this->table, $data );
	}
}
?>