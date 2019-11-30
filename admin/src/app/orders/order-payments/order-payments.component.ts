import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-order-payments',
  templateUrl: './order-payments.component.html'
})
export class OrderPaymentsComponent implements OnChanges {
  loading = false;
  payment = null;
  refund = null;

  @Input()
  order: any;

  constructor(private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.order) {
      this.loading = true;
      this.repository
        .getPayment(this.order.payment.transactionId)
        .subscribe((result: any) => {
          this.loading = false;
          this.payment = result;
        });
    }
  }

  onRefund() {
    this.loading = true;
    this.repository
      .refund(this.order.payment.transactionId)
      .subscribe((result: any) => {
        this.loading = false;
        this.refund = result;
        this.ngOnChanges();
      });
  }
}
