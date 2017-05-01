<?php
class CviLoader{
    protected $actions;

    protected $filters;

    public function __construct() {

        $this->actions = array();
        $this->filters = array();

    }

    public function addAction( $hook, $component, $callback ,$priority = 10,$variables = 2) {
        $this->actions = $this->add( $this->actions, $hook, $component, $callback ,$priority,$variables);
    }

    public function addFilter( $hook, $component, $callback ,$priority = 10, $variables = 2) {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback,$priority,$variables );
    }

    private function add( $hooks, $hook, $component, $callback,$priority = 10,$variables = 2 ) {

        $hooks[] = array(
            'hook'      => $hook,
            'component' => $component,
            'callback'  => $callback,
            'priority' => $priority,
            'variables' => $variables
        );

        return $hooks;

    }

    public function run() {

        foreach ( $this->filters as $hook ) {
            add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ),$hook['priority'],$hook['variables'] );
        }

        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ),$hook['priority'],$hook['variables'] );
        }

    }
}