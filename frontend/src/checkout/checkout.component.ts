import {
  AfterViewInit,
  ChangeDetectorRef,
  Component,
  ElementRef,
  OnDestroy,
  OnInit,
  ViewChild
} from '@angular/core';

import { NgForm } from '@angular/forms';
import { OrderService } from './order.service';

@Component({
  selector: 'app-checkout',
  templateUrl: './checkout.component.html',
})
export class CheckoutComponent implements AfterViewInit, OnInit, OnDestroy {
  @ViewChild('cardInfo', {static: false}) cardInfo: ElementRef;

  // Component state
  cardHandler = this.onChange.bind(this);
  error: string;
  order: any;
  ready = false;
  enrollmentPending = false;
  showSuccessModal = false;
  placedOrder = null;

  // Order placement parameters
  card: any;
  phone: string;
  name: string;
  email: string;

  constructor(private changeDetector: ChangeDetectorRef, private orderService: OrderService) { }

  ngOnInit() {
    this.orderService
      .getCurrentOrder()
      .subscribe((response: any) => {
        this.order = response;
        this.ready = true;
      });
  }

  ngAfterViewInit() {
    const style = {
      base: {
        color: '#8c9b9d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#a9afb7'
        }
      },
      invalid: {
        color: '#e74c3c',
        iconColor: '#e74c3c'
      }
    };

    this.card = elements.create('card', {style});
    this.card.mount(this.cardInfo.nativeElement);
    this.card.addEventListener('change', this.cardHandler);
  }

  ngOnDestroy() {
    this.card.removeEventListener('change', this.cardHandler);
    this.card.destroy();
  }

  onChange({ error }) {
    if (error) {
      this.error = error.message;
    } else {
      this.error = null;
    }

    this.changeDetector.detectChanges();
  }

  async remove(item) {
    this.orderService
      .remove(item)
      .subscribe((response: any) => {
        this.order = response;
      });
  }

  async onSubmit(form: NgForm) {
    const { token, error } = await stripe.createToken(this.card);
    this.enrollmentPending = true;

    if (error) {
      console.log('Stripe error:', error);
      this.enrollmentPending = false;

      return;
    }

    this.orderService
      .placeOrder({
        token: token.id,
        phone: this.phone,
        name: this.name,
        email: this.email,
      })
      .subscribe((response: any) => {
        this.placedOrder = response;
        this.showSuccessModal = true;
      });
  }
}
