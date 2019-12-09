import {
  Component,
  Input,
  OnChanges,
  EventEmitter,
  Output
} from '@angular/core';
import {
  FormArray,
  FormBuilder,
  FormGroup,
  FormControl,
  Validators
} from '@angular/forms';
import { RepositoryService } from '../../repository.service';
import slugify from 'slugify';

@Component({
  selector: 'admin-manage-reviews',
  templateUrl: './manage-reviews.component.html'
})
export class ManageReviewsComponent implements OnChanges {
  @Input()
  update: any;

  loading = false;
  reviews = [];

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnChanges() {
    if (!this.update || !this.update.id) {
      return;
    }

    this.loading = true;
    this.repository
      .find('course/reviews', this.update.id)
      .subscribe((result: any) => {
        this.loading = false;
        this.reviews = result;
      });
  }

  onRemove(id) {
    this.loading = true;
    this.repository.delete('course/reviews', [id]).subscribe((result: any) => {
      this.ngOnChanges();
    });
  }
}
