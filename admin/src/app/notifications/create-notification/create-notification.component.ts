import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { RepositoryService } from '../../services/repository.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'admin-create-notification',
  templateUrl: './create-notification.component.html'
})
export class CreateNotificationComponent implements OnInit {
  pageTitle = '';
  notificationId: string = null;
  loading = false;
  courses = [];
  notificationForm = this.fb.group({
    id: [''],
    course: [''],
    title: ['', Validators.required],
    content: [''],
    contentRich: [''],
    type: ['', Validators.required],
    event: ['', Validators.required],
    fromEmail: [''],
    fromName: [''],
    fromNumber: ['']
  });

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.notificationId = params.id;
      if (params.id === '0') {
        this.notificationId = null;
      }

      if (this.notificationId) {
        this.pageTitle = 'Edit Notification';
      } else {
        this.pageTitle = 'Create New Notification';
      }
    });
  }

  ngOnInit() {
    this.loading = true;
    this.repository.fetch('course', {}).subscribe((result: any) => {
      this.courses = result.items;
      this.loading = false;
    });

    if (this.notificationId) {
      this.loading = true;
      this.repository
        .find('notification', this.notificationId)
        .subscribe((result: any) => {
          this.loading = false;
          this.notificationForm.patchValue(result);
        });
    }
  }

  goBack() {
    this.router.navigate(['/notifications']);
  }

  onSubmit() {
    this.loading = true;

    if (!this.notificationId) {
      delete this.notificationForm.value.id;
      this.repository
        .create('notification', this.notificationForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(['/notifications']);
        });
    } else {
      this.repository
        .update('notification', this.notificationForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(['/notifications']);
        });
    }
  }
}
