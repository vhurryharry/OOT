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
import { RepositoryService } from '../../services/repository.service';

@Component({
  selector: 'admin-edit-order',
  templateUrl: './edit-order.component.html'
})
export class EditOrderComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  order: any;

  loading = false;
  orderForm = this.fb.group({
    id: [''],
    status: ['', Validators.required],
    payment: ['']
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnChanges() {
    console.log(this.order);
    if (this.order) {
      this.loading = true;
      this.repository.find('order', this.order.id).subscribe((result: any) => {
        this.loading = false;
        this.orderForm.patchValue(result);
      });
    }
  }

  onSubmit() {
    this.loading = true;
    this.repository
      .update('order', this.orderForm.value)
      .subscribe((result: any) => {
        this.loading = false;
        this.finished.emit(this.orderForm.value);
      });
  }
}
