import { Injectable } from "@angular/core";
import { Location } from "@angular/common";
import {
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  CanActivate,
  CanLoad,
  Router,
  Route
} from "@angular/router";

import { LoginService } from "../services/login.service";

@Injectable()
export class CourseRoleGuard implements CanActivate, CanLoad {
  constructor(
    private loginService: LoginService,
    private router: Router,
    private location: Location
  ) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): boolean {
    return this.checkPermission(route);
  }

  canLoad(route: Route): boolean {
    return this.checkLoggedIn(route.path);
  }

  checkLoggedIn(url: string): boolean {
    if (this.loginService.isLoggedIn()) {
      return true;
    }

    this.loginService.redirectUrl = url;
    this.loginService.authError = "";
    this.router.navigate(["/login"]);

    return false;
  }

  checkPermission(route: ActivatedRouteSnapshot) {
    const isInstructor = this.loginService.isInstructor();

    switch (route.routeConfig.path) {
      case "new":
        if (!isInstructor) {
          this.router.navigate(["/"]);
        }
        return isInstructor;

      default:
        break;
    }

    return true;
  }
}
