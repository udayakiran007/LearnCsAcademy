import Image from "next/image";
import Link from "next/link";
import React from "react";
import { links } from "./Sidebar.constants";

const Sidebar: React.FC = () => {
  return (
    <aside className="w-64 h-full p-4 space-y-4 bg-gray-50 border-r border-gray-200">
      <nav>
        <ul className="space-y-2">
          {links.map((link) => (
            <li key={link.href}>
              <Link
                href={link.href}
                className="block px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-redeem-secondary rounded-md"
              >
                <Image
                  src={link.icon}
                  alt={`${link.label} icon`}
                  className="inline-block mr-2"
                  width={24}
                  height={24}
                />
                {link.label}
              </Link>
            </li>
          ))}
        </ul>
      </nav>
    </aside>
  );
};
export { Sidebar };
