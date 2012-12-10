<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic_model extends CI_Model {

    public function get_topics()
    {
        $topics = array();
        
        $user = $this->session->userdata('user');
        
        $user_id = ( $user ) ? $user->id : '0';

        $this->db->select('topics.*, votes.user_id AS `user_voted`');
        $this->db->join('votes', 'topics.id = votes.topic_id AND votes.user_id = ' . $user_id, 'left');

        $query = $this->db->get_where('topics');

        foreach ( $query->result() as $topic ) {
            $topic->user_voted = ( !empty($topic->user_voted) );
            $topics[] = $topic;
        }

        return $topics;
    }

    public function get_topic( $topic_id )
    {
        $user = $this->session->userdata('user');
        
        $user_id = ( $user ) ? $user->id : '0';

        $this->db->select('topics.*, votes.user_id AS `user_voted`');
        $this->db->join('votes', 'topics.id = votes.topic_id AND votes.user_id = ' . $user_id, 'left');

        $query = $this->db->get_where('topics', array( 'id' => $topic_id ), 1);
        
        $topic = $query->row();
        
        $topic->user_voted = ( !empty($topic->user_voted) );

        return $topic;
    }

    public function create_topic( $new_topic )
    {
        $this->db->insert('topics', $new_topic);

        return $this->db->insert_id();
    }

    public function add_vote( $topic_id )
    {
        $user = $this->session->userdata('user');

        if ( $user ) {
            $query = $this->db->insert('votes', array(
                'topic_id' => $topic_id,
                'user_id'  => $user->id
            ));
        }
    }

    public function remove_vote( $topic_id )
    {
        $user = $this->session->userdata('user');

        if ( $user ) {
            $this->db->delete('votes', array(
                'topic_id' => $topic_id,
                'user_id'  => $user->id
            ));
        }
    }

}