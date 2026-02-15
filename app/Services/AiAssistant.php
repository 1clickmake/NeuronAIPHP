<?php

namespace App\Services;

use NeuronAI\Agent;
use NeuronAI\Toolbox;

class AiAssistant extends Agent
{
    public function instructions(): string
    {
        return "You are a helpful assistant for the Neuron AI PHP Framework demo. 
                You can help users manage their boards and understand the project structure.";
    }
}
