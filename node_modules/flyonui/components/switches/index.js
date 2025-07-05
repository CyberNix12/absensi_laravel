import switches from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedswitches = addPrefix(switches, prefix);
  addComponents({ ...prefixedswitches });
};
