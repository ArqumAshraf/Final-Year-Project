<?php
$config = array(
        'login' => array(
                            array(
                                    'field' => 'user_email',
                                    'label' => 'Email',
                                    'rules' => 'trim|required'
                                 ),
                            array(
                                    'field' => 'user_pass',
                                    'label' => 'Password',
                                    'rules' => 'required'
                                 )
                            ),          
      'profile' => array(
	                       array(
	                            'field' => 'full_name',
	                            'label' => 'Full Name',
	                            'rules' => 'required|min_length[3]|max_length[20]'
	                         )
	                    ),                                   
	'create_user' => array(
                            array(
                                    'field' => 'full_name',
                                    'label' => 'Full Name',
                                    'rules' => 'required'
                                 ),
                             array(
                                'field' => 'user_pass',
                                'label' => 'Password',
                                'rules' => 'required|alpha_dash|min_length[6]|max_length[20]'
                             ),     
                            array(
                                    'field' => 'user_email',
                                    'label' => 'Email',
                                    'rules' => 'required|trim|valid_email|is_unique[users.user_email]'
                                 )
                            ),
         'addFoodItem' => array(
            array(
                     'field' => 'item_name',
                     'label' => 'Item Name',
                     'rules' => 'required|is_unique[food_item.item_name]'
                  )
         ),
   'create_cat' => array(
      array(
            'field' => 'category_name',
            'label' => 'Title in English',
            'rules' => 'required'
         )
      ),
      'create_author' => array(
         array(
               'field' => 'full_name',
               'label' => 'Author Name',
               'rules' => 'required'
            )
      ),
      'create_article' => array(
         array(
               'field' => 'article_name',
               'label' => 'Title in English',
               'rules' => 'required'
         ),
         array(
            'field' => 'author_id',
            'label' => 'Author',
            'rules' => 'required'
         )
      )
);