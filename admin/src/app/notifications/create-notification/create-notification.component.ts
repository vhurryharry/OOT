import { Component, Input, OnChanges, OnInit, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-notification',
  templateUrl: './create-notification.component.html'
})
export class CreateNotificationComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  courses = [];
  notificationForm = this.fb.group({
    id: [''],
    course: ['', ],
    title: ['', Validators.required],
    content: ['', ],
    contentRich: ['', ],
    type: ['', Validators.required],
    event: ['', Validators.required],
    fromEmail: ['', ],
    fromName: ['', ],
    fromNumber: ['', ],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnInit() {
    this.loading = true;
    this.repository
      .fetch('course', {})
      .subscribe((result: any) => {
        this.courses = result.items;
        this.loading = false;
      });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('notification', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.notificationForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.notificationForm.value.id;
      this.repository
        .create('notification', this.notificationForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.notificationForm.value);
        });
    } else {
      this.repository
        .update('notification', this.notificationForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.notificationForm.value);
        });
    }
  }
}
