import { eventBus } from '../app';

export default {
  bind: (el, binding, vnode) => {
    el.addEventListener('click', () => {
        eventBus.$emit('app:exchange::delete', binding.value)
    });
  },
  unbind: (el) => {
      el.removeEventListener('click');
  }
}
