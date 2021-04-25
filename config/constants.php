<?php

return [

    'questionnaires' => [
        [
            'id' => 1,
            'question' => 'How do you connect to the Internet at home?',
            'choices' => [
                ['id' => 1, 'name' => 'Wifi', 'value' => false],
                ['id' => 2, 'name' => 'Mobile Data', 'value' => false],
                ['id' => 3, 'name' => 'None', 'value' => false],
            ],
            'type' => 'checkboxes',
            'required' => true,
        ],
        [
            'id' => 2,
            'question' => 'What technology devices do you have access to utilize at home?',
            'choices' => [
                ['id' => 1, 'name' => 'Desktop', 'value' => false],
                ['id' => 2, 'name' => 'Smartphones', 'value' => false],
                ['id' => 3, 'name' => 'Tablets', 'value' => false],
            ],
            'type' => 'checkboxes',
            'required' => true,                    
        ],
        [
            'id' => 3,
            'question' => 'What platforms do you use at home that would enable you to access online or remote learning?',
            'choices' => [
                ['id' => 1, 'name' => 'Messenger', 'value' => false],
                ['id' => 2, 'name' => 'Zoom', 'value' => false],
                ['id' => 3, 'name' => 'Email', 'value' => false],
                ['id' => 4, 'name' => 'Google Classroom and Edmodo', 'value' => false],
                ['id' => 5, 'name' => 'None', 'value' => false],
            ],
            'type' => 'checkboxes',
            'required' => true,                      
        ],
        [
            'id' => 4,
            'question' => 'What Flexible Learning Option is convenient for you?',
            'choices' => [
                ['id' => 1, 'name' => 'Modules', 'value' => false],
                ['id' => 2, 'name' => 'Online', 'value' => false],
                ['id' => 3, 'name' => 'Distance Learning thru Social Media Platforms', 'value' => false],
            ],
            'type' => 'checkboxes',
            'required' => true,         
        ],
        [
            'id' => 5,
            'question' => 'What is your average or subscribed internet speed?',
            'type' => 'input',
            'required' => false,            
        ],              
    ]

];