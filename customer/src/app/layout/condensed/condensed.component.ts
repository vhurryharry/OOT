import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-condensed',
  templateUrl: './condensed.component.html'
})
export class CondensedComponent implements OnInit {
  @ViewChild('root') root;

  constructor() {}

  ngOnInit() {}
}
