import { Component, OnInit } from '@angular/core';
import { FormBuilder } from '@angular/forms';
import { Location } from '@angular/common';
import { RepositoryService } from '../../services/repository.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'admin-manage-reviews',
  templateUrl: './manage-reviews.component.html'
})
export class ManageReviewsComponent implements OnInit {
  loading = false;
  reviews = [];
  courseId: string = null;
  pageTitle = 'Manage Course Reviews';

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {
    if (this.courseId && this.courseId !== '0') {
      this.loading = true;
      this.repository
        .find('course/reviews', this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.reviews = result;
        });
    }
  }

  onRemove(id) {
    this.loading = true;
    this.repository.delete('course/reviews', [id]).subscribe((result: any) => {
      this.ngOnInit();
    });
  }

  goBack() {
    this.location.back();
  }
}
