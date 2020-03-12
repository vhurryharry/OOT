import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";
import { Router } from "@angular/router";

@Component({
  selector: "app-footer",
  templateUrl: "./footer.component.html",
  styleUrls: ["./footer.component.scss"]
})
export class FooterComponent implements OnInit {
  @Input()
  public price = -1;

  @Input()
  public status = "";

  @Input()
  public showBadge = true;

  @Output()
  enroll: EventEmitter<any> = new EventEmitter();

  private email = "";

  constructor(private router: Router) {}

  ngOnInit() {}

  formatPrice(price) {
    return (
      "Tuition: $ " + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    );
  }

  onEnroll() {
    this.enroll.emit();
  }

  signup() {
    this.router.navigateByUrl("/signup");
  }
}
