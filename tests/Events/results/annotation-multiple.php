$events->listen(array (
  0 => 'BasicEventFired',
), 'App\Handlers\Events\MultipleEventHandler@handleBasicEvent');
$events->listen(array (
  0 => 'BasicEventFired',
), 'App\Handlers\Events\MultipleEventHandler@handleBasicEventAgain');
$events->listen(array (
  0 => 'AnotherEventFired',
), 'App\Handlers\Events\MultipleEventHandler@handleAnotherEvent');
