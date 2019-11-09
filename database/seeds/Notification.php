<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class Notification extends AbstractSeed
{
    public function run(): void
    {
        $notifications = [
            [
                'title' => 'Welcome to Olive Oil Education Lab',
                'type' => 'email',
                'from_email' => 'no-reply@oliveoilschool.org',
                'from_name' => 'Olive Oil Education Lab',
                'from_number' => '12127293600',
                'event' => 'customer.registered',
                'content' => <<<RAW
Dear {{ customer.name }},

Welcome to Olive Oil Education Lab.
RAW,
                'content_rich' => <<<HTML
<spacer size="16"></spacer>

<container class="header">
  <row>
    <columns>
      <h1 class="text-center">{{ customer.name }}, welcome to Olive Oil Education Lab</h1>
      <center>
        <menu class="text-center">
          <item href="#">About</item>
          <item href="#">Courses</item>
          <item href="#">FAQ</item>
          <item href="#">Contact</item>
        </menu>
      </center>
    </columns>
  </row>
</container>

<container class="body-border">
  <row>
    <columns>
      <spacer size="32"></spacer>

      <center>
        <img src="http://placehold.it/200x200">
      </center>

      <spacer size="16"></spacer>

      <h4>Elit in qui nulla commodo enim ullamco</h4>
      <p>Pariatur tempor sunt culpa veniam sit adipisicing nulla officia in ea laboris sint exercitation sed nisi in reprehenderit esse ut sed dolor ea adipisicing occaecat occaecat.</p>

      <center>
        <menu>
          <item href="#">oliveoilschool.org</item>
          <item href="#">Facebook</item>
          <item href="#">Twitter</item>
          <item href="#">(954)-000-0000</item>
        </menu>
      </center>
    </columns>
  </row>

  <spacer size="16"></spacer>
</container>
HTML,
            ],
        ];

        $this
            ->table('notification')
            ->insert($notifications)
            ->save();
    }
}
