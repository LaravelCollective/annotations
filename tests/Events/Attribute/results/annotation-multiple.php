$events->listen(array (
  0 => 'BasicEventFired',
), 'App\Handlers\Events\Attributes\MultipleEventHandler@handleBasicEvent');
$events->listen(array (
  0 => 'BasicEventFired',
), 'App\Handlers\Events\Attributes\MultipleEventHandler@handleBasicEventAgain');
$events->listen(array (
  0 => 'AnotherEventFired',
), 'App\Handlers\Events\Attributes\MultipleEventHandler@handleAnotherEvent');
