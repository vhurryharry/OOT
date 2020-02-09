import { Injectable } from "@angular/core";

@Injectable()
export class NavigationService {
  currentModule = "content";

  constructor() {}

  switchModule(module) {
    this.currentModule = module;
  }
}
