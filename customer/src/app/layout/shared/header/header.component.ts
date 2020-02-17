import { Component, OnInit, Input, HostListener } from "@angular/core";
import { LoginService } from "src/app/services/login.service";

declare var $: any;

@Component({
  selector: "app-header",
  templateUrl: "./header.component.html",
  styleUrls: ["./header.component.scss"]
})
export class HeaderComponent implements OnInit {
  @Input()
  public lightHeader = false;
  private loggedIn = false;
  private userName = "";
  private menuExpanded = false;

  @HostListener("window:scroll", ["$event"])
  onWindowScroll(e) {
    const element = document.querySelector(".navbar");

    if (window.pageYOffset > 5) {
      element.classList.add("sticky-header");
    } else {
      if (!this.menuExpanded) {
        element.classList.remove("sticky-header");
      }
    }
  }

  constructor(private loginService: LoginService) {
    this.loggedIn = this.loginService.isLoggedIn();
    if (this.loggedIn) {
      const user = this.loginService.getCurrentUser();
      this.userName = user.firstName + " " + user.lastName;
    }
  }

  ngOnInit() {
    $(".navbar-toggler").click(() => {
      this.menuExpanded = !this.menuExpanded;
      if (this.menuExpanded) {
        $(".header").addClass("sticky-header");
        return;
      }

      if (window.pageYOffset <= 5) {
        window.setTimeout(() => {
          $(".header").removeClass("sticky-header");
        }, 500);
      }
    });
  }

  logout() {
    this.loginService.logOut();
    window.location.reload();
  }
}
